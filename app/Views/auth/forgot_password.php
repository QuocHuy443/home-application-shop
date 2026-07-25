<?php

/**
 * Trang Quên Mật Khẩu
 * @var string $error
 * @var string $success
 */
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="bg-warning-subtle text-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-key fa-xl"></i>
                    </div>
                    <h4 class="fw-bold">Quên Mật Khẩu?</h4>
                    <p class="text-muted small">Nhập email đăng ký của bạn. Chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu
                        qua email.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger rounded-3 small py-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="alert alert-success rounded-3 small py-2" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>

                <form action="/forgot-password" method="POST">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Địa chỉ Email đã đăng ký</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted"><i
                                    class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 rounded-end-3"
                                placeholder="nhapemail@example.com" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm mb-3">
                        Gửi Mật Khẩu Mới / Link Khôi Phục
                    </button>
                </form>

                <div class="text-center">
                    <a href="/login" class="text-secondary small text-decoration-none">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang Đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>