<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role_id', '!=', 1)->count();
        $revenue = Order::where('status', 'completed')->sum('total_amount');
        
        $recentOrders = Order::with('user')->orderBy('id', 'desc')->take(5)->get();

        $this->view('admin/dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'revenue' => $revenue,
            'recentOrders' => $recentOrders
        ], 'admin');
    }
}
