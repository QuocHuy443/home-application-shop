<?php

namespace App\Controllers\Admin;
//Quản lý danh mục
use App\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    // 1. Lấy danh sách toàn bộ danh mục
    public function index()
{
    $query = Category::withCount('products');

    // Tìm kiếm theo tên hoặc slug
    if (!empty($_GET['search'])) {

        $search = trim($_GET['search']);

        $query->where(function ($q) use ($search) {

            $q->where('name', 'LIKE', '%' . $search . '%')
              ->orWhere('slug', 'LIKE', '%' . $search . '%');

        });
    }

    // Sắp xếp mới nhất
    $categories = $query->orderBy('id', 'DESC')->paginate(10)->appends($_GET);

    $this->view(
        'admin/categories/index',
        [
            'categories' => $categories
        ],
        'admin'
    );
}

    // 2. Thêm danh mục mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $data = $_POST;

        if (empty($data['name'])) {
            $this->redirect('/admin/categories?error=missing_name');
        }

        $slug = $data['slug'] ?? $this->createSlug($data['name']);

        if (!empty($data['id'])) {
            $category = Category::find($data['id']);
            if ($category) {
                $existingSlug = Category::where('slug', $slug)->where('id', '!=', $category->id)->exists();
                if ($existingSlug) {
                    $this->redirect('/admin/categories?error=slug_exists');
                }

                $category->update([
                    'name'      => $data['name'],
                    'slug'      => $slug,
                    'is_active' => isset($data['is_active']) ? 1 : 0,
                ]);
                $this->redirect('/admin/categories');
            }
        }

        // Kiểm tra trùng Slug
        if (Category::where('slug', $slug)->exists()) {
            $this->redirect('/admin/categories?error=slug_exists');
        }

        Category::create([
            'name'      => $data['name'],
            'slug'      => $slug,
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        $this->redirect('/admin/categories');
    }

    // 3. Cập nhật danh mục
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $data = $_POST;

        $category = Category::find($id);
        if (!$category) {
            $this->back();
        }

        $category->update([
            'name'      => $data['name'] ?? $category->name,
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        $this->redirect('/admin/categories');
    }

    // 4. Xóa danh mục
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $category = Category::find($id);
        if (!$category) {
            $this->back();
        }

        if ($category->products()->count() > 0) {
            $this->redirect('/admin/categories?error=has_products');
        }

        $category->delete();
        $this->redirect('/admin/categories');
    }

    // Helper tạo slug từ tên tiếng Việt
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
