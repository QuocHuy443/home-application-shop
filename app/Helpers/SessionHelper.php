<?php
// Helper quản lý Session & Auth trạng thái

namespace App\Helpers;

class SessionHelper
{
    // Khởi tạo session an toàn
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    // Lưu thông tin user vào Session sau khi đăng nhập thành công
    public static function login($user)
    {
        self::init();
        $_SESSION['user_id']      = $user->id;
        $_SESSION['user_name']    = $user->name;
        $_SESSION['user_email']   = $user->email;
        $_SESSION['user_phone']   = $user->phone ?? '';
        $_SESSION['user_address'] = $user->address ?? '';
        $_SESSION['role_id']      = $user->role_id;
        $_SESSION['role_name']    = $user->role->name ?? 'customer';
    }

    // Cập nhật lại thông tin session khi người dùng chỉnh sửa hồ sơ hoặc đặt hàng
    public static function updateSessionInfo($phone = null, $address = null, $name = null)
    {
        self::init();
        if ($name !== null) $_SESSION['user_name'] = $name;
        if ($phone !== null) $_SESSION['user_phone'] = $phone;
        if ($address !== null) $_SESSION['user_address'] = $address;
    }

    // Đăng xuất: Xóa sạch Session
    public static function logout()
    {
        self::init();
        $_SESSION = []; // Xóa toàn bộ biến trong session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    // Kiểm tra xem đã đăng nhập chưa
    public static function isLoggedIn()
    {
        self::init();
        return isset($_SESSION['user_id']);
    }

    // Lấy thông tin user hiện tại
    public static function user()
    {
        self::init();
        if (self::isLoggedIn()) {
            return [
                'id'        => $_SESSION['user_id'],
                'name'      => $_SESSION['user_name'],
                'email'     => $_SESSION['user_email'],
                'phone'     => $_SESSION['user_phone'] ?? '',
                'address'   => $_SESSION['user_address'] ?? '',
                'role_id'   => $_SESSION['role_id'],
                'role_name' => $_SESSION['role_name'],
            ];
        }
        return null;
    }
}
