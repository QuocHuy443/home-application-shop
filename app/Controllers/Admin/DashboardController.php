<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Helpers\SessionHelper;

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

    // Hiển thị trang hồ sơ Admin
    public function profile()
    {
        $currentUser = SessionHelper::user();
        $userId = is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null);

        $admin = User::find($userId);

        $this->view('admin/profile/index', [
            'admin' => $admin
        ], 'admin');
    }

    // Xử lý cập nhật hồ sơ
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/profile');
            return;
        }

        $currentUser = SessionHelper::user();
        $userId = is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null);
        $admin = User::find($userId);

        if (!$admin) {
            $this->redirect('/admin/profile');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Dữ liệu cập nhật khớp chuẩn với cột trong CSDL users
        $updateData = [
            'name'  => $name,
            'phone' => $phone,
        ];

        // Nếu người dùng nhập mật khẩu mới
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                $this->redirect('/admin/profile');
                return;
            }
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $admin->update($updateData);

        // Đồng bộ lại Session thông tin người dùng đang đăng nhập
        if (isset($_SESSION['user'])) {
            if (is_array($_SESSION['user'])) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
            } elseif (is_object($_SESSION['user'])) {
                $_SESSION['user']->name = $name;
                $_SESSION['user']->phone = $phone;
            }
        }

        $_SESSION['success'] = 'Cập nhật thông tin hồ sơ thành công!';
        $this->redirect('/admin/profile');
    }
}