<?php
// Chặn người không phải Admin và tài khoản bị khóa
namespace App\Middleware;

use App\Helpers\SessionHelper;
use App\Models\User;

class AdminMiddleware
{
    public static function handle()
    {
        SessionHelper::init();

        // 1. Kiểm tra xem đã đăng nhập chưa
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['auth_error'] = 'Vui lòng đăng nhập tài khoản Quản trị viên!';
            header('Location: /login');
            exit();
        }

        $sessionUser = SessionHelper::user();
        // DÒNG MỚI ĐÃ FIX SẠCH LỖI:
$userId = $sessionUser ? (is_array($sessionUser) ? ($sessionUser['id'] ?? null) : ($sessionUser->id ?? null)) : null;

        // 2. Lấy dữ liệu user mới nhất từ DB để kiểm tra trạng thái khóa tài khoản thực tế
        $user = User::with('role')->find($userId);

        if (!$user || (int)($user->status ?? 1) === 0) {
            // Nếu tài khoản đã bị khóa trong CSDL -> Xóa session đăng xuất ngay lập tức
            SessionHelper::logout();
            $_SESSION['auth_error'] = 'Tài khoản Admin của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ!';
            header('Location: /login');
            exit();
        }

        // 3. Kiểm tra vai trò xem có phải Admin không
        $roleName = $user->role->name ?? $sessionUser['role_name'] ?? '';

        if ($roleName !== 'admin') {
            http_response_code(403);
            echo "403 - Bạn không có quyền truy cập vào trang Quản trị Admin!";
            exit();
        }
    }
}