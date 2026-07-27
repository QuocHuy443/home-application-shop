<?php
//Quản lý sản phẩm & ảnh
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Helpers\FileUploader;

class ProductController extends Controller
{
    // 1. Lấy danh sách sản phẩm (có kèm Danh mục)
   public function index()
{
    $query = Product::with(['category', 'images']);

    // Tìm kiếm theo tên hoặc slug
    if (!empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%')
              ->orWhere('slug', 'LIKE', '%' . $search . '%');
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
                // Còn hàng
                $query->where('stock', '>', 5);
                break;

            case 'low_stock':
                // Sắp hết hàng (1 - 5)
                $query->whereBetween('stock', [1, 5]);
                break;

            case 'out_of_stock':
                // Hết hàng
                $query->where('stock', '=', 0);
                break;
        }
    }

    $products = $query
        ->orderBy('id', 'DESC')
        ->get();

    $categories = Category::orderBy('name')->get();

    $this->view(
        'admin/products/index',
        [
            'products' => $products,
            'categories' => $categories
        ],
        'admin'
    );
}

    // 2. Thêm sản phẩm mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $data = $_POST;
        $files = $_FILES;

        if (empty($data['name']) || empty($data['price']) || empty($data['category_id'])) {
            $this->redirect('/admin/products?error=missing_fields');
        }

        $slug = $data['slug'] ?? $this->createSlug($data['name']);

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

                if (isset($files['thumbnail']) && $files['thumbnail']['error'] === UPLOAD_ERR_OK) {
                    $updateData['thumbnail'] = FileUploader::uploadSingle($files['thumbnail']);
                }

                $product->update($updateData);
                $this->redirect('/admin/products');
            }
        }

        // Upload ảnh đại diện nếu có
        $thumbnailPath = null;
        if (isset($files['thumbnail']) && $files['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnailPath = FileUploader::uploadSingle($files['thumbnail']);
        }

        Product::create([
            'name'        => $data['name'],
            'slug'        => $slug,
            'description' => $data['description'] ?? '',
            'price'       => $data['price'],
            'stock'       => $data['stock'] ?? 0,
            'thumbnail'   => $thumbnailPath ?? 'uploads/products/default.jpg',
            'category_id' => $data['category_id'],
        ]);

        $this->redirect('/admin/products');
    }

    // 3. Cập nhật sản phẩm
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $data = $_POST;

        $product = Product::find($id);
        if (!$product) {
            $this->back();
        }

        $product->update([
            'name'        => $data['name'] ?? $product->name,
            'price'       => $data['price'] ?? $product->price,
            'stock'       => $data['stock'] ?? $product->stock,
            'description' => $data['description'] ?? $product->description,
            'category_id' => $data['category_id'] ?? $product->category_id,
        ]);

        $this->redirect('/admin/products');
    }

    // 4. Xóa sản phẩm
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $product = Product::find($id);
        if (!$product) {
            $this->back();
        }

        // Xóa các ảnh chi tiết phụ trước
        ProductImage::where('product_id', $id)->delete();
        $product->delete();

        $this->redirect('/admin/products');
    }

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
