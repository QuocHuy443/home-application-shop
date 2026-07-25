<?php

/**
 * Admin Dashboard Main Layout
 * @var string $viewPath
 */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - HomeApp Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom Admin Stylesheet -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="bg-light">

    <div class="d-flex min-vh-100">
        <!-- Sidebar Navigation -->
        <?php require_once __DIR__ . '/../partials/admin_sidebar.php'; ?>

        <!-- Main Workspace Area -->
        <div class="flex-grow-1 d-flex flex-column">

            <!-- Topbar Header Area -->
            <header
                class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center sticky-top shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <button id="sidebarToggle" class="btn btn-light btn-sm border-0 d-md-none">
                        <i class="fa-solid fa-bars fs-5"></i>
                    </button>
                    <span class="fw-bold text-secondary">Hệ thống Quản lý Cửa hàng Gia Dụng</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="/" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fa-solid fa-globe me-1"></i> Xem Website Client
                    </a>

                    <div class="dropdown">
                        <button
                            class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3"
                            type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user-gear text-primary"></i>
                            <span class="fw-semibold">Admin</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="/admin/profile"><i
                                        class="fa-solid fa-id-card me-2"></i>Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="/admin/settings"><i
                                        class="fa-solid fa-sliders me-2"></i>Cài đặt</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i
                                        class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Dynamic Body View -->
            <main class="p-4 flex-grow-1">
                <div class="container-fluid">
                    <?php require_once $viewPath; ?>
                </div>
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-top py-3 text-center text-muted fs-7">
                &copy; <?= date('Y') ?> HomeApp Admin Dashboard. Developed for Home Application Shop.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/admin.js"></script>
</body>

</html>