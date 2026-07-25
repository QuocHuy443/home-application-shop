<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $this->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images'])->find($id);
        if (!$product) {
            $this->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        $this->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
}
