<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<?php
$email = $email ?? '';
$token = $token ?? '';
?>

<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7">

```
        <div class="text-center mb-4">
            <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                 style="width: 90px; height: 90px;">
                <i class="fa-solid fa-key fa-2x"></i>
            </div>

            <h2 class="fw-bold mb-2">Đặt lại mật khẩu</h2>

            <p class="text-muted">
                Tạo mật khẩu mới để tiếp tục sử dụng tài khoản HomeAppShop.
            </p>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/reset-password">
                    <?= \App\Helpers\CsrfHelper::csrfField() ?>

                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-lock text-muted"></i>
                            </span>

                            <input type="password"
                                   name="password"
                                   id="passwordInput"
                                   class="form-control border-start-0 border-end-0"
                                   placeholder="Nhập mật khẩu mới"
                                   required>

                            <button type="button"
                                    class="btn btn-outline-secondary border-start-0"
                                    onclick="togglePassword('passwordInput', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        <small class="text-muted">
                            Mật khẩu nên có ít nhất 8 ký tự.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu</label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-lock text-muted"></i>
                            </span>

                            <input type="password"
                                   name="password_confirmation"
                                   id="confirmPasswordInput"
                                   class="form-control border-start-0 border-end-0"
                                   placeholder="Nhập lại mật khẩu"
                                   required>

                            <button type="button"
                                    class="btn btn-outline-secondary border-start-0"
                                    onclick="togglePassword('confirmPasswordInput', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-check me-2"></i>
                        Cập nhật mật khẩu
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="/login"
                       class="text-decoration-none fw-semibold text-primary">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-muted small">
            <i class="fa-solid fa-shield-halved me-1"></i>
            Vì lý do bảo mật, liên kết này chỉ sử dụng được một lần.
        </div>
    </div>
</div>
```

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
