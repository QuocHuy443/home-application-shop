<?php
/**
 * Trang Thanh Toán (Checkout)
 * @var array $cartItems
 * @var float $totalAmount
 * @var object|array $currentUser
 */

$userFullname = '';
$userPhone    = '';
$userEmail    = '';
$userAddress  = '';

if (!empty($currentUser)) {
    if (is_object($currentUser)) {
        $userFullname = $currentUser->fullname ?? $currentUser->name ?? '';
        $userPhone    = $currentUser->phone ?? '';
        $userEmail    = $currentUser->email ?? '';
        $userAddress  = $currentUser->address ?? '';
    } elseif (is_array($currentUser)) {
        $userFullname = $currentUser['fullname'] ?? $currentUser['name'] ?? '';
        $userPhone    = $currentUser['phone'] ?? '';
        $userEmail    = $currentUser['email'] ?? '';
        $userAddress  = $currentUser['address'] ?? '';
    }
}
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/cart" class="text-decoration-none">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h4 class="fw-bold mb-4"><i class="fa-solid fa-credit-card text-primary me-2"></i>Thanh Toán Đơn Hàng</h4>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
    <form action="/checkout/process" method="POST" id="checkoutForm">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
    
        <div class="row g-4">
            <!-- CỘT BÊN TRÁI: THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC THANH TOÁN -->
            <div class="col-lg-7">
                <!-- Thông tin người nhận -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-user me-2 text-primary"></i>Thông Tin Giao Hàng</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Họ và tên <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="fullname" id="shipping_fullname" class="form-control rounded-3" placeholder="Nguyễn Văn A"
                                required 
                                oninvalid="this.setCustomValidity('Vui lòng nhập họ và tên người nhận!')" 
                                oninput="this.setCustomValidity('')"
                                value="<?= htmlspecialchars($userFullname) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Số điện thoại <span
                                    class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="shipping_phone" class="form-control rounded-3" placeholder="0901234567"
                                required 
                                oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại người nhận!')" 
                                oninput="this.setCustomValidity('')"
                                value="<?= htmlspecialchars($userPhone) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ Email</label>
                            <input type="email" name="email" id="shipping_email" class="form-control rounded-3"
                                placeholder="email@example.com"
                                oninvalid="this.setCustomValidity('Vui lòng nhập đúng định dạng Email!')" 
                                oninput="this.setCustomValidity('')"
                                value="<?= htmlspecialchars($userEmail) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ nhận hàng <span
                                    class="text-danger">*</span></label>
                            <textarea name="address" id="shipping_address" class="form-control rounded-3" rows="2"
                                placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/TP..."
                                required 
                                oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ giao hàng chi tiết!')" 
                                oninput="this.setCustomValidity('')"><?= htmlspecialchars($userAddress) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Ghi chú đơn hàng (Tuỳ chọn)</label>
                            <textarea name="note" id="shipping_note" class="form-control rounded-3" rows="2"
                                placeholder="Ghi chú về thời gian giao hàng, hướng dẫn chi tiết..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-wallet me-2 text-primary"></i>Phương Thức Thanh Toán
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        <!-- Option 1: COD -->
                        <label
                            class="border rounded-4 p-3 d-flex align-items-center gap-3 cursor-pointer border-primary bg-primary-subtle">
                            <input type="radio" name="payment_method" value="cod" checked class="form-check-input my-0">
                            <div class="bg-white p-2 rounded-3 text-primary">
                                <i class="fa-solid fa-truck-ramp-box fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Thanh toán khi nhận hàng (COD)</h6>
                                <small class="text-muted">Bạn chỉ thanh toán khi đã nhận được và kiểm tra sản
                                    phẩm.</small>
                            </div>
                        </label>

                        <!-- Option 2: Chuyển khoản QR -->
                        <label class="border rounded-4 p-3 d-flex align-items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                class="form-check-input my-0">
                            <div class="bg-white p-2 rounded-3 text-success">
                                <i class="fa-solid fa-qrcode fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Chuyển khoản qua Mã QR / Ngân hàng</h6>
                                <small class="text-muted">Quét mã QR thanh toán nhanh qua ứng dụng
                                    Bank/Momo/ZaloPay.</small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CỘT BÊN PHẢI: TÓM TẮT ĐƠN HÀNG -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 position-sticky" style="top: 20px;">
                    <h5 class="fw-bold mb-3">Tóm Tắt Đơn Hàng (<?= count($cartItems) ?>)</h5>

                    <!-- Danh sách SP xem nhanh -->
                    <div class="overflow-auto pe-1 mb-3" style="max-height: 280px;">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <img src="/<?= $item['thumbnail'] ?>" class="rounded-3 border"
                                        style="width: 50px; height: 50px; object-fit: contain;">
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-truncate small fw-semibold" style="max-width: 170px;">
                                        <?= htmlspecialchars($item['name']) ?></h6>
                                    <small class="text-muted"><?= number_format($item['price']) ?>đ</small>
                                </div>
                            </div>
                            <span
                                class="fw-bold text-dark small"><?= number_format($item['price'] * $item['quantity']) ?>đ</span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tính tiền -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Tạm tính:</span>
                        <span class="fw-semibold small"><?= number_format($totalAmount ?? 0) ?> VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Phí vận chuyển:</span>
                        <span class="text-success fw-semibold small">Miễn phí</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Tổng thanh toán:</span>
                        <span class="text-danger fw-bold fs-4"><?= number_format($totalAmount ?? 0) ?> VNĐ</span>
                    </div>

                    <!-- Nút hoàn tất -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-check me-2"></i> Đặt Hàng Ngay
                    </button>

                    <a href="/cart" class="text-center text-decoration-none small text-muted mt-3 d-block">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </form>
    <?php else: ?>
    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
        <i class="fa-solid fa-bag-shopping fa-3x text-muted mb-3"></i>
        <h5>Không có sản phẩm nào để thanh toán!</h5>
        <p class="text-muted small mb-4">Vui lòng chọn sản phẩm vào giỏ hàng trước khi đặt hàng.</p>
        <a href="/products" class="btn btn-primary rounded-pill px-4">Khám phá sản phẩm</a>
    </div>
    <?php endif; ?>
</div>

<style>
/* Đổi border highlight khi chọn Radio */
label.cursor-pointer {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

label.cursor-pointer:hover {
    border-color: var(--bs-primary) !important;
}
</style>