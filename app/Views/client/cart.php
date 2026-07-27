<?php

/**
 * Trang Giỏ Hàng
 * @var array $cartItems
 * @var float $totalAmount
 */
?>

<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping text-primary me-2"></i>Giỏ Hàng Của Bạn</h4>

    <?php if (!empty($cartItems)): ?>
        <div class="row g-4">
            <!-- Bảng danh sách sản phẩm trong giỏ -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th style="width: 130px;">Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="/<?= $item['thumbnail'] ?>" class="rounded-3 border"
                                                    style="width: 60px; height: 60px; object-fit: contain;">
                                                <div>
                                                    <h6 class="mb-0 text-truncate" style="max-width: 200px;">
                                                        <?= htmlspecialchars($item['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['price']) ?>đ</td>
                                        <td>
                                            <form action="/cart/update" method="POST" class="d-flex align-items-center">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
                                                
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <input type="number" name="quantity"
                                                    class="form-control form-control-sm text-center rounded-3"
                                                    value="<?= $item['quantity'] ?>" min="1" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-danger">
                                            <?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                                        <td class="text-end">
                                            <form action="/cart/remove" method="POST" class="d-inline">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
                                                
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle"
                                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng & Thanh toán -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Tóm Tắt Đơn Hàng</h5>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold"><?= number_format($totalAmount ?? 0) ?> VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success fw-semibold">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Tổng cộng:</span>
                        <span class="text-danger fw-bold fs-4"><?= number_format($totalAmount ?? 0) ?> VNĐ</span>
                    </div>

                    <?php if (!empty($isLoggedIn)): ?>
                        <a href="/checkout" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                            Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                            Đăng nhập để thanh toán <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-solid fa-cart-arrow-down fa-3x text-muted mb-3"></i>
            <h5>Giỏ hàng của bạn đang trống!</h5>
            <p class="text-muted small mb-4">Hãy tham khảo các sản phẩm chất lượng cao của chúng tôi ngay hôm nay.</p>
            <a href="/products" class="btn btn-primary rounded-pill px-4">Khám phá sản phẩm</a>
        </div>
    <?php endif; ?>
</div>