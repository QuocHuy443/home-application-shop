<?php

/**
 * Giao diện Đặt Hàng & Thanh Toán Thành Công
 * @var object $order
 */
$payment = $order->payment;
$isPaid = ($payment && $payment->payment_status === 'paid');
$paymentMethod = $payment->payment_method ?? 'cod';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Card kết quả -->
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-center">
                <!-- Icon thành công -->
                <div class="mb-4">
                    <div
                        class="success-icon-wrap d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle shadow-sm">
                        <i class="fa-solid fa-circle-check fa-4x text-success"></i>
                    </div>
                </div>

                <h3 class="fw-bold text-dark mb-2">Đặt Hàng & Thanh Toán Thành Công!</h3>
                <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại <strong>Home Appliance Shop</strong>. Đơn hàng của
                    bạn đang được hệ thống xử lý.</p>

                <!-- Badge trạng thái thanh toán -->
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6">
                        Mã đơn hàng: <strong class="text-primary"><?= htmlspecialchars($order->order_code) ?></strong>
                    </span>

                    <?php if ($isPaid): ?>
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-6">
                            <i class="fa-solid fa-check-double me-1"></i> ĐÃ THANH TOÁN (VIETQR)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill fs-6">
                            <i class="fa-solid fa-truck-fast me-1"></i> COD (Thanh toán khi nhận hàng)
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Bảng chi tiết đơn hàng -->
                <div class="text-start bg-light rounded-4 p-4 mb-4 border">
                    <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-receipt me-2 text-primary"></i>Chi
                        Tiết Giao Hàng</h6>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <span class="text-muted d-block">Người nhận:</span>
                            <strong class="text-dark"><?= htmlspecialchars($order->shipping_name) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block">Số điện thoại:</span>
                            <strong class="text-dark"><?= htmlspecialchars($order->shipping_phone) ?></strong>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block">Địa chỉ nhận hàng:</span>
                            <strong class="text-dark"><?= htmlspecialchars($order->shipping_address) ?></strong>
                        </div>
                        <?php if (!empty($order->note)): ?>
                            <div class="col-12">
                                <span class="text-muted d-block">Ghi chú:</span>
                                <span class="text-dark italic"><?= htmlspecialchars($order->note) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold mt-4 mb-3 border-bottom pb-2"><i
                            class="fa-solid fa-box-open me-2 text-primary"></i>Danh Sách Sản Phẩm</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm align-middle mb-0">
                            <tbody>
                                <?php foreach ($order->items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php
                                                $image = $item->product->thumbnail ?? '';

                                                if (
                                                    str_starts_with($image, 'http://') ||
                                                    str_starts_with($image, 'https://')
                                                ) {
                                                    $imageUrl = $image;
                                                } else {
                                                    $imageUrl = '/' . ltrim($image, '/');
                                                }
                                                ?>

                                                <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                    alt="<?= htmlspecialchars($item->product->name ?? 'Sản phẩm', ENT_QUOTES, 'UTF-8') ?>"
                                                    class="rounded border"
                                                    style="width: 38px; height: 38px; object-fit: contain;">

                                                <span class="fw-medium text-truncate" style="max-width: 280px;">
                                                    <?= htmlspecialchars($item->product->name ?? 'Sản phẩm', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center text-muted">x<?= $item->quantity ?></td>
                                        <td class="text-end fw-bold"><?= number_format($item->price * $item->quantity) ?>
                                            VNĐ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-6">Tổng tiền đã xác nhận:</span>
                        <span class="fw-bold text-danger fs-4"><?= number_format($order->total_amount) ?> VNĐ</span>
                    </div>
                </div>

                <!-- Nút điều hướng -->
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="/products" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Tiếp tục mua sắm
                    </a>
                    <a href="/" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                        <i class="fa-solid fa-house me-2"></i> Về Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-icon-wrap {
        width: 100px;
        height: 100px;
        animation: pulseCheck 1.8s infinite ease-in-out;
    }

    @keyframes pulseCheck {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(25, 135, 84, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
        }
    }
</style>