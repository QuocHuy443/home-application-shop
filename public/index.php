<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 1. Tự động nạp các Class qua Composer Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Cấu hình Error Handling & Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    \App\Helpers\Logger::error("Error [$errno]: $errstr in $errfile on line $errline");
    return false;
});

set_exception_handler(function($exception) {
    \App\Helpers\Logger::error("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    http_response_code(500);
    echo "<h1>Đã xảy ra lỗi hệ thống!</h1>";
    if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        echo "<pre>" . $exception->getMessage() . "</pre>";
    }
});

// 2. Nạp config Database (DB init)
require_once __DIR__ . '/../config/database.php';

// 3. Khởi tạo Router mới
$router = new \App\Core\Router();

// 4. Load danh sách Routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// 5. Lấy URL và Method hiện tại từ trình duyệt (Đã bóc tách Query String)
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$uriPath = parse_url($rawUri, PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Bắt Request _method override cho form DELETE/PUT (nếu cần sau này)
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// =========================================================================
// 5.1. KIỂM TRA CHẾ ĐỘ BẢO TRÌ (MAINTENANCE MODE)
// Ngoại lệ: Cho phép truy cập đường dẫn Quản trị (/admin), Đăng nhập (/login), Đăng xuất (/logout)
// =========================================================================
$isExemptRoute = (strpos($uriPath, '/admin') === 0 || strpos($uriPath, '/login') === 0 || strpos($uriPath, '/logout') === 0);

if (!$isExemptRoute) {
    if (class_exists('\App\Middleware\MaintenanceMiddleware')) {
        \App\Middleware\MaintenanceMiddleware::handle();
    }
}

// 6. Xử lý Route
$router->dispatch($method, $rawUri);