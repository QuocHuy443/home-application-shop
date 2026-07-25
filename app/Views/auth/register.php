<?php
/**
 * Trang Đăng Ký Tài Khoản
 * @var string $error
 */
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-user-plus fa-xl"></i>
                    </div>
                    <h4 class="fw-bold">Tạo Tài Khoản Mới</h4>
                    <p class="text-muted small">Đăng ký để trải nghiệm mua sắm tiện lợi tại HomeApp Shop</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger rounded-3 small py-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <form action="/register" method="POST">
                    <?= \App\Helpers\CsrfHelper::csrfField() ?>
                    <!-- Họ và tên -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Họ và tên <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-id-card"></i></span>
                            <input type="text" name="fullname" class="form-control border-start-0 rounded-end-3"
                                placeholder="Nguyễn Văn A" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Địa chỉ Email <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 rounded-end-3"
                                placeholder="email@example.com" required>
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Số điện thoại <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-phone"></i></span>
                            <input type="tel" name="phone" class="form-control border-start-0 rounded-end-3"
                                placeholder="0901234567" required>
                        </div>
                    </div>

                    <!-- Mật khẩu & Nhập lại Mật khẩu -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Mật khẩu <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" id="regPassword" class="form-control rounded-3"
                                placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Xác nhận mật khẩu <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" id="regConfirmPassword"
                                class="form-control rounded-3" placeholder="Nhập lại mật khẩu" minlength="6" required>
                        </div>
                    </div>

                    <!-- Đồng ý điều khoản -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-input-label small text-muted" for="terms">
                            Tôi đồng ý với <a href="#" class="text-primary text-decoration-none">Điều khoản dịch vụ</a>
                            và <a href="#" class="text-primary text-decoration-none">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm mb-3">
                        Đăng Ký Tài Khoản
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-muted small mb-0">Đã có tài khoản? <a href="/login"
                            class="text-primary fw-semibold text-decoration-none">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>