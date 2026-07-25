<?php
//Chặn người không phải Admin
namespace App\Middleware;

use App\Helpers\SessionHelper;

class AdminMiddleware
{
    public static function handle()
    {
        SessionHelper::init();

        // 1. Kiểm tra đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        // 2. Kiểm tra vai trò xem có phải admin không
        $user = SessionHelper::user();
        if ($user['role_name'] !== 'admin') {
            // Nếu không phải admin -> Đẩy về trang chủ hoặc báo lỗi 403
            http_response_code(403);
            echo "403 - Bạn không có quyền truy cập vào trang Quản trị Admin!";
            exit();
        }
    }
}
