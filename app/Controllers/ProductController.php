<?php
//Xem & tìm kiếm sản phẩm
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // 1. Lấy danh sách sản phẩm (có hỗ trợ Lọc & Tìm kiếm)
    public function index()
    {
        $query = Product::with(['category', 'images']);

        $filters = $_GET;

        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Tìm kiếm theo tên sản phẩm đồ gia dụng (VD: "Nồi chiên", "Bếp từ")
        if (!empty($filters['keyword'])) {
            $query->where('name', 'LIKE', '%' . $filters['keyword'] . '%');
        }
        
        // Lọc theo giá
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sắp xếp
        $sortBy = $filters['sort'] ?? 'newest';
        if ($sortBy === 'price_asc') {
            $query->orderBy('price', 'ASC');
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('price', 'DESC');
        } else {
            $query->orderBy('id', 'DESC'); // Mới nhất
        }

        $perPage = 12;

        $products = $query->paginate($perPage)->appends($_GET);
        $categories = Category::where('is_active', 1)->get();

        $this->view('client/products/index', [
            'products' => $products,
            'categories' => $categories
        ], 'main');
    }

    // 2. Chi tiết 1 sản phẩm theo Slug (Router mới truyền slug, router cũ dùng ?slug=)
    public function show($slug = null)
    {
        if (!$slug) {
            $slug = $_GET['slug'] ?? null;
        }

        $product = Product::with(['category', 'images'])->where('slug', $slug)->first();

        if (!$product) {
            $this->redirect('/products');
        }

        // Lấy thêm các sản phẩm liên quan cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $this->view('client/products/detail', [
            'product' => $product,
            'related' => $relatedProducts
        ], 'main');
    }
}
