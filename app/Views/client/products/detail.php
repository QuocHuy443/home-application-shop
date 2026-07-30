<?php

/**
 * Trang chi tiết sản phẩm
 * @var object $product
 */
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/products" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($product->name ?? 'Chi tiết sản phẩm') ?></li>
        </ol>
    </nav>

    <?php if (!empty($product)): ?>
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
            <div class="row g-4">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-5 text-center">
                    <div class="p-3 border rounded-4 bg-light mb-3">
                        <im<?php
                            $thumbnail = $product->thumbnail ?? '';

                            // Kiểm tra nếu là URL thì dùng trực tiếp, nếu không thì thêm dấu /
                            if (
                                filter_var($thumbnail, FILTER_VALIDATE_URL)
                            ) {
                                $imageUrl = $thumbnail;
                            } else {
                                $imageUrl = '/' . ltrim($thumbnail, '/');
                            }
                            ?> <img src="<?= htmlspecialchars($imageUrl) ?>" class="img-fluid rounded-3" id="mainImage"
                            alt="<?= htmlspecialchars($product->name) ?>"
                            style="max-height:350px; width:100%; object-fit:contain;"
                            onerror="this.src='/uploads/products/no-image.png';">
                    </div>
                </div>

                <!-- Thông tin & Nút mua -->
                <div class="col-md-7">
                    <h3 class="fw-bold text-dark mb-2"><?= htmlspecialchars($product->name) ?></h3>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">Còn hàng
                            (<?= $product->stock ?>)</span>
                        <span class="text-muted small">Mã SP: #<?= $product->id ?></span>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4">
                        <span class="text-danger fw-bold fs-2"><?= number_format($product->price) ?> VNĐ</span>
                    </div>

                    <!-- Mô tả ngắn -->
                    <p class="text-secondary small mb-4">
                        <?= nl2br(htmlspecialchars($product->description ?? 'Thiet bi gia dung cao cap, tiet kiem dien nang, bao hanh chinh hang.')) ?>
                    </p>

                    <!-- Form thêm vào giỏ hàng -->
                    <form action="/cart/add" method="POST" class="mb-4">
                        <?= \App\Helpers\CsrfHelper::csrfField() ?>


                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                        <div class="row g-3 align-items-center mb-4">
                            <div class="col-auto">
                                <label class="fw-semibold small">Số lượng:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="quantity" class="form-control rounded-3 text-center" value="1"
                                    min="1" max="<?= $product->stock ?>" style="width: 90px;">
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit"
                                class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm flex-grow-1">
                                <i class="fa-solid fa-cart-plus me-2"></i> Thêm Vào Giỏ Hàng
                            </button>
                        </div>
                    </form>

                    <!-- Cam kết dịch vụ -->
                    <div class="border-top pt-3 row g-2 text-center text-muted small">
                        <div class="col-4"><i class="fa-solid fa-truck text-primary me-1"></i> Giao nhanh 2h</div>
                        <div class="col-4"><i class="fa-solid fa-rotate-left text-primary me-1"></i> Đổi trả 30 ngày</div>
                        <div class="col-4"><i class="fa-solid fa-shield text-primary me-1"></i> Bảo hành 12-24T</div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
            <h5>Sản phẩm không tồn tại hoặc đã bị xóa.</h5>
            <a href="/products" class="btn btn-primary rounded-pill mt-2">Quay lại cửa hàng</a>
        </div>
    <?php endif; ?>
</div>