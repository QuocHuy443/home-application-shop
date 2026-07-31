<?php
// Quản lý sản phẩm & ảnh
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Helpers\FileUploader;

class ProductController extends Controller
{
    // 1. Lấy danh sách sản phẩm (có kèm Danh mục và Bộ sưu tập ảnh)
    public function index()
    {
        $query = Product::with(['category', 'images']);

        // Tìm kiếm theo tên hoặc slug
        if (!empty($_GET['search'])) {
            $search = trim($_GET['search']);
            // Escape các ký tự đặc biệt % và _ trong SQL LIKE
            $escapedSearch = addcslashes($search, '%_');

            $query->where(function ($q) use ($escapedSearch) {
                $q->where('name', 'LIKE', '%' . $escapedSearch . '%')
                    ->orWhere('slug', 'LIKE', '%' . $escapedSearch . '%');
            });
        }

        // Lọc theo danh mục
        if (!empty($_GET['category_id'])) {
            $query->where('category_id', $_GET['category_id']);
        }

        // Lọc theo trạng thái tồn kho
        if (!empty($_GET['stock_status'])) {
            switch ($_GET['stock_status']) {
                case 'in_stock':
                    $query->where('stock', '>', 5);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock', [1, 5]);
                    break;
                case 'out_of_stock':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        $products = $query->orderBy('id', 'DESC')->paginate(10)->appends($_GET);
        $categories = Category::orderBy('name')->get();

        $this->view(
            'admin/products/index',
            [
                'products'   => $products,
                'categories' => $categories
            ],
            'admin'
        );
    }

    // 2. Thêm hoặc Cập nhật sản phẩm
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $data = $_POST;
        $files = $_FILES;

        if (empty($data['name']) || empty($data['price']) || empty($data['category_id'])) {
            $this->redirect('/admin/products?error=missing_fields');
            return;
        }

        $slug = !empty($data['slug']) ? $this->createSlug($data['slug']) : $this->createSlug($data['name']);

        // Trường hợp UPDATE
        if (!empty($data['id'])) {
            $product = Product::find($data['id']);
            if ($product) {
                $updateData = [
                    'name'        => $data['name'],
                    'slug'        => $slug,
                    'description' => $data['description'] ?? $product->description,
                    'price'       => $data['price'],
                    'stock'       => $data['stock'] ?? $product->stock,
                    'category_id' => $data['category_id'],
                ];

                // Upload file mới
if (
    isset($files['thumbnail']) &&
    $files['thumbnail']['error'] === UPLOAD_ERR_OK
) {

    $this->deletePhysicalFile($product->thumbnail);

    $updateData['thumbnail'] =
        FileUploader::uploadSingle($files['thumbnail']);

}
// Nếu nhập link
elseif (!empty($data['thumbnail_url'])) {

    // Nếu ảnh cũ là file local thì xóa
    if (
        !str_starts_with($product->thumbnail, 'http://') &&
        !str_starts_with($product->thumbnail, 'https://')
    ) {
        $this->deletePhysicalFile($product->thumbnail);
    }

    $updateData['thumbnail'] =
        trim($data['thumbnail_url']);
}
                $product->update($updateData);

                // Upload thêm gallery images nếu có
                if (isset($files['images'])) {
                    $this->uploadGalleryImages($product->id, $files['images']);
                }

                $this->redirect('/admin/products');
                return; // Dừng luồng xử lý sau khi redirect
            }
        }

        // Trường hợp CREATE NEW
      $thumbnailPath = null;

if (
    isset($files['thumbnail']) &&
    $files['thumbnail']['error'] === UPLOAD_ERR_OK
) {

    $thumbnailPath =
        FileUploader::uploadSingle($files['thumbnail']);

}
elseif (!empty($data['thumbnail_url'])) {

    $thumbnailPath =
        trim($data['thumbnail_url']);

}
        $product = Product::create([
            'name'        => $data['name'],
            'slug'        => $slug,
            'description' => $data['description'] ?? '',
            'price'       => $data['price'],
            'stock'       => $data['stock'] ?? 0,
            'thumbnail'   => $thumbnailPath ?? 'uploads/products/default.jpg',
            'category_id' => $data['category_id'],
        ]);

        // Upload gallery images cho sản phẩm mới
        if (isset($files['images'])) {
            $this->uploadGalleryImages($product->id, $files['images']);
        }

        $this->redirect('/admin/products');
        return;
    }

    // 3. Cập nhật thông tin nhanh
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $product = Product::find($id);
        if (!$product) {
            $this->back();
            return;
        }

        $data = $_POST;
        $product->update([
            'name'        => $data['name'] ?? $product->name,
            'price'       => $data['price'] ?? $product->price,
            'stock'       => $data['stock'] ?? $product->stock,
            'description' => $data['description'] ?? $product->description,
            'category_id' => $data['category_id'] ?? $product->category_id,
        ]);

        $this->redirect('/admin/products');
        return;
    }

    // 4. Xóa sản phẩm và tài nguyên liên quan
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $product = Product::find($id);
        if (!$product) {
            $this->back();
            return;
        }

        // 1. Xóa ảnh thumbnail thực tế (tránh xóa ảnh default)
        $this->deletePhysicalFile($product->thumbnail);

        // 2. Xóa danh sách ảnh bộ sưu tập (File + Database)
        $galleryImages = ProductImage::where('product_id', $id)->get();
        foreach ($galleryImages as $image) {
            $this->deletePhysicalFile($image->image_url);
            $image->delete();
        }

        // 3. Xóa sản phẩm
        $product->delete();

        $this->redirect('/admin/products');
        return;
    }

    // Helper: Xử lý upload danh sách ảnh phụ
    private function uploadGalleryImages($productId, $files)
    {
        if (empty($files['name'][0])) {
            return;
        }

        // Giả định FileUploader::uploadMultiple xử lý mảng $_FILES['images']
        if (method_exists(FileUploader::class, 'uploadMultiple')) {
            $uploadedPaths = FileUploader::uploadMultiple($files);
            foreach ($uploadedPaths as $path) {
                ProductImage::create([
                    'product_id' => $productId,
                    'image_url'  => $path,
                ]);
            }
        }
    }

    // Helper: Xóa file vật lý trên ổ cứng
  private function deletePhysicalFile($filePath)
{
    if (empty($filePath)) {
        return;
    }

    // Không xóa nếu là link Internet
    if (
        str_starts_with($filePath,'http://') ||
        str_starts_with($filePath,'https://')
    ) {
        return;
    }

    if (str_contains($filePath,'default.jpg')) {
        return;
    }

    $fullPath =
        $_SERVER['DOCUMENT_ROOT'].'/'.
        ltrim($filePath,'/');

    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}
    // Helper: Tạo Slug chuẩn SEO tiếng Việt
    private function createSlug($str)
    {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $str);
        $str = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $str);
        $str = preg_replace('/[iíìỉĩị]/u', 'i', $str);
        $str = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $str);
        $str = preg_replace('/[úùủũụưứừửữự]/u', 'u', $str);
        $str = preg_replace('/[ýỳỷỹỵ]/u', 'y', $str);
        $str = preg_replace('/[đ]/u', 'd', $str);
        $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
        $str = preg_replace('/[\s-]+/', '-', trim($str));
        return $str;
    }
}