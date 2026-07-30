<?php

/**
 * Trang chủ Client
 * @var array $categories
 * @var array $latestProducts
 */
?>

<!-- Banner Quảng Cáo -->
<section class="hero-section py-4">
    <div class="container">
        <div class="row g-3">
            <!-- Banner chính bên trái -->
            <div class="col-lg-8">
                <div id="heroCarousel" class="carousel slide shadow-sm rounded-4 overflow-hidden"
                    data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active bg-primary text-white p-5" style="min-height: 360px;">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <span
                                    class="badge bg-warning text-dark align-self-start mb-2 px-3 py-2 rounded-pill fw-bold">Khuyến
                                    mãi cực hot</span>
                                <h1 class="display-6 fw-bold mb-3">Gia Dụng Thông Minh - Nâng Tầm Cuộc Sống</h1>
                                <p class="fs-6 mb-4 opacity-75">Ưu đãi giảm giá tới 40% cho các dòng máy hút bụi robot
                                    và nồi chiên không dầu thế hệ mới.</p>
                                <div>
                                    <a href="/products"
                                        class="btn btn-light btn-lg text-primary fw-bold rounded-pill px-4 shadow-sm">Khám
                                        phá ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2 Banner phụ bên phải -->
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div
                    class="card border-0 bg-secondary text-white rounded-4 shadow-sm p-4 flex-grow-1 justify-content-center">
                    <span class="text-warning fw-bold small text-uppercase">Bán Chạy Nhất</span>
                    <h5 class="fw-bold mb-1">Nồi Chiên Không Dầu</h5>
                    <p class="small opacity-75 mb-2">Công nghệ đối lưu Rapid Air</p>
                    <a href="/products" class="text-white text-decoration-none fw-semibold small">Xem ngay <i
                            class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                <div
                    class="card border-0 bg-dark text-white rounded-4 shadow-sm p-4 flex-grow-1 justify-content-center">
                    <span class="text-info fw-bold small text-uppercase">Mới Ra Mắt</span>
                    <h5 class="fw-bold mb-1">Robot Hút Bụi Lau Nhà</h5>
                    <p class="small opacity-75 mb-2">Định vị Lidar thông minh</p>
                    <a href="/products" class="text-info text-decoration-none fw-semibold small">Tìm hiểu thêm <i
                            class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Danh mục sản phẩm -->
<section class="categories-section py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="fa-solid fa-layer-group text-primary me-2"></i>Danh Mục Sản Phẩm</h4>
            <a href="/products" class="text-primary text-decoration-none fw-semibold small">Tất cả danh mục <i
                    class="fa-solid fa-chevron-right ms-1"></i></a>
        </div>

        <div class="row g-3">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <div class="col-lg-2 col-md-3 col-6">
                        <a href="/products?category_id=<?= $cat->id ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm text-center p-3 rounded-4 h-100 transition">
                                <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-kitchen-set fa-lg"></i>
                                </div>
                                <h6 class="card-title text-dark fw-semibold small mb-0"><?= htmlspecialchars($cat->name) ?></h6>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php
                $demoCats = ['Nồi chiên', 'Máy hút bụi', 'Lò vi sóng', 'Máy xay sinh tố', 'Nồi cơm điện', 'Quạt thông minh'];
                foreach ($demoCats as $name):
                ?>
                    <div class="col-lg-2 col-md-3 col-6">
                        <a href="/products" class="text-decoration-none">
                            <div class="card border-0 shadow-sm text-center p-3 rounded-4 h-100">
                                <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-kitchen-set fa-lg"></i>
                                </div>
                                <h6 class="card-title text-dark fw-semibold small mb-0"><?= $name ?></h6>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Sản phẩm mới nhất -->
<section class="products-section py-4 mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="fa-solid fa-fire text-danger me-2"></i>Sản Phẩm Mới Về</h4>
            <a href="/products" class="text-primary text-decoration-none fw-semibold small">Xem tất cả <i
                    class="fa-solid fa-chevron-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php if (!empty($latestProducts)): ?>
                <?php foreach ($latestProducts as $product): ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                            <a href="/products/detail?slug=<?= $product->slug ?>" class="text-decoration-none">
                                <div class="p-3 text-center bg-white">
                                    <?php
                                    $image = $product->thumbnail ?? '';

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
                                        alt="<?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') ?>" class="img-fluid"
                                        style="height: 160px; object-fit: contain;">
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column p-3">
                                <a href="/products/detail?slug=<?= $product->slug ?>" class="text-dark text-decoration-none">
                                    <h6 class="card-title fw-semibold text-truncate mb-2"
                                        title="<?= htmlspecialchars($product->name) ?>"><?= htmlspecialchars($product->name) ?>
                                    </h6>
                                </a>
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span
                                            class="text-danger fw-bold fs-5 mb-0"><?= number_format($product->price) ?>đ</span>
                                    </div>
                                    <form action="/cart/add" method="POST">
                                        <?= \App\Helpers\CsrfHelper::csrfField() ?>


                                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-semibold">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Thêm giỏ hàng
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="fa-solid fa-boxes-packing fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Chưa có sản phẩm nào được cập nhật.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>