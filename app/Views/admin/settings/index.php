<?php

/**
 * View: Cài Đặt & Cấu Hình Hệ Thống - Admin
 * @var array $settings
 */

use App\Helpers\CsrfHelper;

require_once __DIR__ . '/../../layouts/admin.php';
?>

<div class="content-wrapper p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-gear me-2 text-primary"></i> Cài Đặt & Cấu Hình Hệ Thống</h4>
            <p class="text-muted small mb-0">Quản lý các thông tin chung, tùy chỉnh màu sắc giao diện và trạng thái hệ thống.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> Cập nhật cấu hình hệ thống thành công!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="/admin/settings/update" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
        <?= CsrfHelper::csrfField() ?>

        <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-globe me-2"></i> 1. Thông Tin Chung Website</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Tên Website / Cửa Hàng <span class="text-danger">*</span></label>
                <input type="text" name="site_name" class="form-control rounded-3" 
                       value="<?= htmlspecialchars($settings['site_name'] ?? 'HomeApp Shop - Đồ Gia Dụng Thông Minh') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Số Điện Thoại Hotline</label>
                <input type="text" name="site_hotline" class="form-control rounded-3" 
                       value="<?= htmlspecialchars($settings['site_hotline'] ?? '1900 8888') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Email Hỗ Trợ</label>
                <input type="email" name="site_email" class="form-control rounded-3" 
                       value="<?= htmlspecialchars($settings['site_email'] ?? 'support@homeapp.vn') ?>">
            </div>
        </div>

        <hr class="text-muted my-3 opacity-25">

        <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-palette me-2"></i> 2. Tùy Chỉnh Giao Diện & Màu Sắc</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Màu Chủ Đạo Website (Primary Color)</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="color" name="primary_color" class="form-control form-control-color border-0 cursor-pointer" 
                           value="<?= $settings['primary_color'] ?? '#0d6efd' ?>" title="Chọn màu sắc chủ đạo">
                    <span class="fw-bold text-secondary"><?= $settings['primary_color'] ?? '#0d6efd' ?></span>
                </div>
                <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">* Màu sắc này sẽ được áp dụng cho toàn bộ nút bấm, header và highlight ở trang Khách hàng.</small>
            </div>

            <div class="col-md-8">
                <label class="form-label fw-semibold small">Logo / Favicon Website</label>
                <input type="file" name="site_logo" class="form-control rounded-3" accept="image/*">
                <?php if (!empty($settings['site_logo'])): ?>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <small class="text-muted">Logo hiện tại:</small>
                        <img src="/<?= htmlspecialchars($settings['site_logo']) ?>" height="35" class="border p-1 rounded bg-light">
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-12 mt-3">
                <label class="form-label fw-semibold small">Thông Báo Chạy Chữ (Announcement Banner)</label>
                <input type="text" name="announcement_text" class="form-control rounded-3" 
                       placeholder="Ví dụ: Ưu đãi giảm giá 30% cho các dòng Robot Hút Bụi thông minh..." 
                       value="<?= htmlspecialchars($settings['announcement_text'] ?? '') ?>">
                <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">* Để trống nếu không muốn hiển thị thanh thông báo trên đỉnh trang chủ.</small>
            </div>
        </div>

        <hr class="text-muted my-3 opacity-25">

        <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-shield-halved me-2"></i> 3. Trạng Thái Hệ Thống</h5>
        <div class="form-check form-switch mb-4">
            <input class="form-check-input cursor-pointer" type="checkbox" name="maintenance_mode" id="maintenanceMode" 
                   <?= ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
            <label class="form-check-label fw-bold text-danger cursor-pointer" for="maintenanceMode">
                Bật Chế Độ Bảo Trì (Maintenance Mode)
            </label>
            <small class="text-muted d-block" style="font-size: 0.85rem;">
                Khi bật chế độ này, người dùng thông thường sẽ không truy cập được trang web (chỉ tài khoản Quản trị viên Admin mới có thể xem).
            </small>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Cấu Hình Hệ Thống
            </button>
        </div>
    </form>
</div>