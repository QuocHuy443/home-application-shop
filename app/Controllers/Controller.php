<?php

namespace App\Controllers;

use App\Helpers\View;

class Controller
{
    // Hàm hỗ trợ gọi giao diện (View)
    protected function view($viewName, $data = [], $layout = 'main')
    {
        View::render($viewName, $data, $layout);
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }

    protected function back()
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($url);
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
