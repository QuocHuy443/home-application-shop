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