<?php

/**
 * Trang Đăng Nhập
 * @var string $error
 */
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-right-to-bracket fa-xl"></i>
                    </div>
                    <h4 class="fw-bold">Đăng Nhập</h4>
                    <p class="text-muted small">Chào mừng bạn quay trở lại với HomeApp Shop</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger rounded-3 small py-2" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="POST">
                    <?= \App\Helpers\CsrfHelper::csrfField() ?>
                    <!-- Email / Tên đăng nhập -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email hoặc Tên đăng nhập</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-envelope"></i></span>
                            <input type="text" name="username_email" class="form-control border-start-0 rounded-end-3"
                                placeholder="nhapemail@example.com" required>
                        </div>
                    </div>

                    <!-- Mật khẩu -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label fw-semibold small mb-1">Mật khẩu</label>
                            <a href="/forgot-password" class="text-primary small text-decoration-none">Quên mật
                                khẩu?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="passwordInput"
                                class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary border-start-0 rounded-end-3" type="button"
                                onclick="togglePassword('passwordInput', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Ghi nhớ đăng nhập -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-input-label small text-muted cursor-pointer" for="rememberMe">
                            Ghi nhớ tài khoản trên thiết bị này
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm mb-3">
                        Đăng Nhập
                    </button>
                </form>

                <div class="text-center">
                    <p class="text-muted small mb-0">Chưa có tài khoản? <a href="/register"
                            class="text-primary fw-semibold text-decoration-none">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>