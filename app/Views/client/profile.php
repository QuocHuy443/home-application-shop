<?php
/**
 * View: Hồ sơ cá nhân (Client)
 * @var object|array $user
 */

require_once __DIR__ . '/../layouts/main.php';

// Trích xuất thông tin người dùng an toàn
$displayName   = is_array($user) ? ($user['name'] ?? '') : ($user->name ?? '');
$displayEmail  = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');
$displayPhone  = is_array($user) ? ($user['phone'] ?? '') : ($user->phone ?? '');
$displayAddress = is_array($user) ? ($user['address'] ?? '') : ($user->address ?? '');
$createdAt     = is_array($user) ? ($user['created_at'] ?? 'now') : ($user->created_at ?? 'now');
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar tài khoản -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-center p-3 border-bottom">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto fs-2 fw-bold mb-3" style="width: 70px; height: 70px;">
                        <?= strtoupper(substr(!empty($displayName) ? $displayName : ($displayEmail ?? 'U'), 0, 1)) ?>
                    </div>
                    <h6 class="fw-bold text-dark mb-1"><?= !empty($displayName) ? htmlspecialchars($displayName) : 'Thành viên' ?></h6>
                    <small class="text-muted d-block text-truncate"><?= htmlspecialchars($displayEmail) ?></small>
                </div>

                <div class="list-group list-group-flush mt-3 border-0">
                    <a href="/profile" class="list-group-item list-group-item-action border-0 rounded-3 active fw-semibold my-1">
                        <i class="fa-solid fa-user me-2"></i> Thông tin tài khoản
                    </a>
                    <a href="/logout" class="list-group-item list-group-item-action border-0 rounded-3 text-danger my-1">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Khung chỉnh sửa thông tin -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-id-card me-2 text-primary"></i>Hồ Sơ Của Tôi</h5>

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

                <form action="/profile/update" method="POST">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Họ và Tên</label>
                            <input type="text" name="name" class="form-control rounded-3" value="<?= htmlspecialchars($displayName) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Số Điện Thoại</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="<?= htmlspecialchars($displayPhone) ?>" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ Email (Không thể đổi)</label>
                            <input type="email" class="form-control rounded-3 bg-light" value="<?= htmlspecialchars($displayEmail) ?>" readonly disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ giao hàng mặc định</label>
                            <textarea name="address" class="form-control rounded-3" rows="2" placeholder="Nhập địa chỉ nhận hàng"><?= htmlspecialchars($displayAddress) ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-key me-2 text-warning"></i>Đổi Mật Khẩu (Để trống nếu giữ nguyên)</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Mật Khẩu Mới</label>
                            <input type="password" name="new_password" class="form-control rounded-3" placeholder="Nhập mật khẩu mới" autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Xác Nhận Mật Khẩu Mới</label>
                            <input type="password" name="confirm_password" class="form-control rounded-3" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>