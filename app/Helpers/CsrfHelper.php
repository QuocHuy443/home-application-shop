<?php

namespace App\Helpers;

class CsrfHelper
{
    public static function init()
    {
        SessionHelper::init();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function getToken()
    {
        self::init();
        return $_SESSION['csrf_token'];
    }

    public static function csrfField()
    {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    public static function validate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::init();
            $token = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                http_response_code(403);
                die('CSRF token validation failed. Bạn không có quyền thực hiện hành động này.');
            }
        }
    }
}
