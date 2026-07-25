<?php

/**
 * Main Layout for Client Pages
 * @var string $viewPath
 */

use App\Models\Setting;

// 1. Đọc tất cả cài đặt cấu hình từ CSDL
$settingsRaw = [];
try {
    $settingsRaw = Setting::pluck('key_value', 'key_name')->toArray();
} catch (\Exception $e) {
    // Fallback nếu database chưa có bảng settings
    $settingsRaw = [];
}

// 2. Lấy các giá trị cấu hình mặc định
$siteName         = $settingsRaw['site_name'] ?? 'HomeApp Shop - Đồ Gia Dụng Thông Minh';
$primaryColor     = $settingsRaw['primary_color'] ?? '#0d6efd';
$announcementText = $settingsRaw['announcement_text'] ?? '';
$siteLogo         = $settingsRaw['site_logo'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?></title>

    <?php if (!empty($siteLogo)): ?>
        <link rel="icon" href="/<?= htmlspecialchars($siteLogo) ?>" type="image/x-icon">
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom Client Stylesheet -->
    <link rel="stylesheet" href="/assets/css/client.css">

    <!-- DYNAMIC THEME COLOR (Màu chủ đạo điều chỉnh từ trang Admin Settings) -->
    <style>
        :root {
            --primary-color: <?= $primaryColor ?>;
            --bs-primary: <?= $primaryColor ?>;
            --bs-primary-rgb: <?= implode(',', sscanf($primaryColor, "#%02x%02x%02x")) ?>;
        }

        /* Ghi đè màu chủ đạo Bootstrap 5 */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- BANNER THÔNG BÁO CHẠY CHỮ ĐỘNG (Bật/Tắt từ Cài đặt hệ thống) -->
    <?php if (!empty($announcementText)): ?>
        <div class="bg-warning text-dark text-center py-2 px-3 fw-medium small border-bottom shadow-sm">
            <i class="fa-solid fa-bullhorn me-2 text-danger"></i> <?= htmlspecialchars($announcementText) ?>
        </div>
    <?php endif; ?>

    <!-- Header Navigation -->
    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <!-- Main Content Dynamic View -->
    <main class="flex-grow-1">
        <?php require_once $viewPath; ?>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/client.js"></script>
</body>

</html>