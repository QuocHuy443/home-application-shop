<?php
/**
 * View: Hồ sơ Admin
 * @var object|array $admin
 */

require_once __DIR__ . '/../../layouts/admin.php';

// Trích xuất thông tin khớp với cấu trúc bảng users
$displayName = is_array($admin) ? ($admin['name'] ?? '') : ($admin->name ?? '');
$displayEmail = is_array($admin) ? ($admin['email'] ?? '') : ($admin->email ?? '');
$displayPhone = is_array($admin) ? ($admin['phone'] ?? '') : ($admin->phone ?? '');
$createdAt    = is_array($admin) ? ($admin['created_at'] ?? 'now') : ($admin->created_at ?? 'now');
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-id-card me-2 text-primary"></i> Hồ Sơ Cá Nhân (Admin)</h4>
            <p class="text-muted small mb-0">Quản lý và cập nhật thông tin tài khoản quản trị viên.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Thẻ tóm tắt thông tin bên trái -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto fs-1 fw-bold" style="width: 90px; height: 90px;">
                        <?= strtoupper(substr(!empty($displayName) ? $displayName : ($displayEmail ?? 'A'), 0, 1)) ?>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-1"><?= !empty($displayName) ? htmlspecialchars($displayName) : 'Chưa cập nhật' ?></h5>
                <span class="badge bg-danger-subtle text-danger mb-3 px-3 py-2 rounded-pill fw-semibold">
                    <i class="fa-solid fa-user-shield me-1"></i> Quản trị viên (Admin)
                </span>
                <hr class="my-3">
                <div class="text-start small text-muted">
                    <div class="mb-2"><i class="fa-solid fa-envelope me-2 text-primary"></i><strong>Email:</strong> <?= htmlspecialchars($displayEmail) ?></div>
                    <div class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i><strong>SĐT:</strong> <?= htmlspecialchars($displayPhone ?? 'Chưa có') ?></div>
                    <div><i class="fa-solid fa-calendar-day me-2 text-primary"></i><strong>Ngày tham gia:</strong> <?= date('d/m/Y', strtotime($createdAt)) ?></div>
                </div>
            </div>
        </div>

        <!-- Form chỉnh sửa bên phải -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-user-pen me-2 text-primary"></i>Cập Nhật Thông Tin</h6>
                
                <form action="/admin/profile/update" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Họ và Tên</label>
                            <input type="text" name="name" class="form-control rounded-3" value="<?= htmlspecialchars($displayName) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Số Điện Thoại</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="<?= htmlspecialchars($displayPhone) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ Email (Không thể đổi)</label>
                            <input type="email" class="form-control rounded-3 bg-light" value="<?= htmlspecialchars($displayEmail) ?>" readonly disabled>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-key me-2 text-warning"></i>Đổi Mật Khẩu (Để trống nếu giữ nguyên)</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Mật Khẩu Mới</label>
                            <input type="password" name="new_password" class="form-control rounded-3" placeholder="Nhập mật khẩu mới">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Xác Nhận Mật Khẩu Mới</label>
                            <input type="password" name="confirm_password" class="form-control rounded-3" placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>