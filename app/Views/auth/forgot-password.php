<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7">

```
        <div class="text-center mb-4">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                 style="width: 90px; height: 90px;">
                <i class="fa-solid fa-unlock-keyhole fa-2x"></i>
            </div>

            <h2 class="fw-bold mb-2">Quên mật khẩu?</h2>

            <p class="text-muted">
                Đừng lo lắng! Hãy nhập email đã đăng ký,
                chúng tôi sẽ gửi cho bạn liên kết đặt lại mật khẩu.
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

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/forgot-password">
                    <?= \App\Helpers\CsrfHelper::csrfField() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Địa chỉ email</label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-envelope text-muted"></i>
                            </span>

                            <input type="email"
                                   name="email"
                                   class="form-control border-start-0"
                                   placeholder="example@gmail.com"
                                   required>
                        </div>

                        <small class="text-muted">
                            Nhập đúng email bạn đã dùng khi đăng ký tài khoản.
                        </small>
                    </div>

                    <button type="submit"
                            class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-2"></i>
                        Gửi liên kết đặt lại
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
            Liên kết đặt lại mật khẩu sẽ hết hạn sau <strong>60 phút</strong>.
        </div>
    </div>
</div>
```

</div>
