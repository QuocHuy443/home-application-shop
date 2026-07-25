<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh mục đang hoạt động
        $categories = Category::where('is_active', 1)->get();

        // Lấy 8 sản phẩm mới nhất
        $latestProducts = Product::orderBy('id', 'DESC')->take(8)->get();

        // Gọi View
        $this->view('client/home', [
            'categories' => $categories,
            'latestProducts' => $latestProducts
        ], 'main');
    }
}
