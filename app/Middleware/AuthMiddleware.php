<?php
//Chặn người chưa đăng nhập
namespace App\Middleware;

use App\Helpers\SessionHelper;

class AuthMiddleware
{
    public static function handle()
    {
        SessionHelper::init();

        // Nếu chưa đăng nhập -> Chuyển hướng sang trang đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }
}