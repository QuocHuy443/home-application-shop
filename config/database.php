<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

// 1. Đọc file .env ở thư mục gốc
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$capsule = new Capsule();

// 2. Cấu hình thông số kết nối MySQL từ file .env
$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? '3306',
    'database'  => $_ENV['DB_DATABASE'] ?? 'home_appliance_shop',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// 3. Khởi tạo Eloquent ORM cho toàn bộ ứng dụng
$capsule->setAsGlobal();
$capsule->bootEloquent();

// 4. Cấu hình Pagination cho môi trường không có Laravel framework
\Illuminate\Pagination\Paginator::currentPageResolver(function ($pageName = 'page') {
    $page = $_GET[$pageName] ?? 1;
    if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
        return (int)$page;
    }
    return 1;
});

\Illuminate\Pagination\Paginator::currentPathResolver(function () {
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
});