<?php

use App\Helpers\SessionHelper;
use App\Models\Setting;

// 1. Lấy thông tin người dùng đang đăng nhập từ SessionHelper
$currentUser = SessionHelper::user();

// 2. Đọc cấu hình Hotline và Email từ bảng Settings
$settingsRaw = [];
try {
    $settingsRaw = Setting::pluck('key_value', 'key_name')->toArray();
} catch (\Exception $e) {
    $settingsRaw = [];
}

$siteHotline = $settingsRaw['site_hotline'] ?? '1900 8888';
$siteEmail   = $settingsRaw['site_email'] ?? 'support@homeapp.vn';

// 3. Xử lý URL hiện tại để active menu chính xác
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// 4. Tính tổng số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart']['items']) && is_array($_SESSION['cart']['items'])) {
    foreach ($_SESSION['cart']['items'] as $item) {
        $cartCount += (int)($item['quantity'] ?? $item['qty'] ?? 1);
    }
}

// 5. Kiểm tra quyền Admin
$isAdmin = false;
if ($currentUser) {
    $roleName = strtolower((string)($currentUser['role']['name'] ?? $currentUser['role'] ?? ''));
    $roleId   = (int)($currentUser['role_id'] ?? 0);
    if ($roleName === 'admin' || $roleId === 1) {
        $isAdmin = true;
    }
}

// 6. Xử lý rút gọn tên người dùng hiển thị trên Header
$rawName   = is_array($currentUser) ? ($currentUser['name'] ?? $currentUser['email'] ?? 'Thành viên') : ($currentUser->name ?? $currentUser->email ?? 'Thành viên');
$nameParts = explode(' ', trim($rawName));
$shortName = end($nameParts); // Lấy từ cuối cùng (ví dụ: "Nguyễn Văn Tâm" -> "Tâm")

if (mb_strlen($shortName) > 8) {
    $shortName = mb_substr($shortName, 0, 8) . '...';
}
?>

<!-- Topbar Section -->
<div class="topbar bg-dark text-white py-1 fs-7">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="topbar-info d-flex gap-3">
            <span><i class="fa-solid fa-headset me-1 text-primary"></i> Hotline: <?= htmlspecialchars($siteHotline) ?></span>
            <span class="d-none d-md-inline"><i class="fa-solid fa-envelope me-1 text-primary"></i> <?= htmlspecialchars($siteEmail) ?></span>
        </div>
        <div class="topbar-links d-flex gap-3 align-items-center">
            <a href="/products" class="text-white-50 text-decoration-none">Sản phẩm</a>
            
            <?php if ($currentUser): ?>
                <!-- Đã đăng nhập: Click vào tên sẽ vào /profile, di chuột vào hiện full tên nhờ attr title -->
                <a href="/profile" class="text-warning fw-semibold text-decoration-none" title="<?= htmlspecialchars($rawName) ?>">
                    <i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($shortName) ?>
                </a>
                <a href="/logout" class="text-white-50 text-decoration-none"><i class="fa-solid fa-right-from-bracket me-1"></i>Đăng xuất</a>
            <?php else: ?>
                <!-- Chưa đăng nhập -->
                <a href="/login" class="text-white text-decoration-none"><i class="fa-solid fa-right-to-bracket me-1"></i>Đăng nhập</a>
                <a href="/register" class="text-white-50 text-decoration-none">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Header Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary fs-4" href="/">
            <i class="fa-solid fa-house-laptop fa-lg me-2"></i>
            <span>HomeApp<span class="text-dark">Shop</span></span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Collapse Content -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Global Search Form -->
            <form action="/products" method="GET" class="d-flex mx-auto my-2 my-lg-0 w-100 max-w-500">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control border-end-0 rounded-start-pill ps-3"
                        placeholder="Tìm kiếm máy hút bụi, nồi chiên, lò vi sóng..."
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                    <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <!-- Navigation Links -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center fw-medium">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentUri === '/') ? 'active text-primary' : '' ?>"
                        href="/">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (str_contains($currentUri, '/products')) ? 'active text-primary' : '' ?>"
                        href="/products">Sản phẩm</a>
                </li>

                <!-- Cart Action Button -->
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="/cart"
                        class="btn btn-outline-primary rounded-pill position-relative px-3 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Giỏ hàng</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cartCount ?>
                        </span>
                    </a>
                </li>

                <!-- Nút chuyển nhanh vào trang Admin nếu là tài khoản Admin -->
                <?php if ($isAdmin): ?>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="/admin/dashboard" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">
                            <i class="fa-solid fa-user-shield me-1 text-warning"></i> Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>