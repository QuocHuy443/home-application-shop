<?php

/**
 * Trang danh sách sản phẩm & Lọc
 * @var array $products
 * @var array $categories
 * @var int $totalProducts
 */
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- BỘ LỌC CỘT BÊN TRÁI -->
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-filter text-primary me-2"></i>Bộ Lọc</h5>
                <form action="/products" method="GET">
                    <!-- Tìm kiếm từ khóa -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control form-control-sm rounded-3"
                            placeholder="Tên sản phẩm..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                    </div>

                    <!-- Lọc theo danh mục -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Danh mục</label>
                        <select name="category_id" class="form-select form-select-sm rounded-3">
                            <option value="">Tất cả danh mục</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"
                                        <?= ($_GET['category_id'] ?? '') == $cat->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Lọc theo khoảng giá -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Khoảng giá (VNĐ)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="min_price" class="form-control form-control-sm rounded-3"
                                placeholder="Từ" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                            <span>-</span>
                            <input type="number" name="max_price" class="form-control form-control-sm rounded-3"
                                placeholder="Đến" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-semibold">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Áp dụng
                    </button>
                    <a href="/products" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-semibold mt-2">Xóa
                        bộ lọc</a>
                </form>
            </div>
        </aside>

        <!-- DANH SÁCH SẢN PHẨM BÊN PHẢI -->
        <main class="col-lg-9">
            <!-- Thanh Sắp xếp -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm mb-4">
                <span class="text-muted small">Hiển thị <strong><?= count($products ?? []) ?></strong> sản phẩm</span>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted text-nowrap">Sắp xếp:</label>
                    <select class="form-select form-select-sm rounded-3 style-select" onchange="location = this.value;">
                        <option value="/products?sort=newest">Mới nhất</option>
                        <option value="/products?sort=price_asc">Giá tăng dần</option>
                        <option value="/products?sort=price_desc">Giá giảm dần</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản Phẩm -->
            <div class="row g-3">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 col-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                                <a href="/products/detail?slug=<?= $product->slug ?>" class="text-decoration-none">
                                    <div class="p-3 text-center bg-white">
                                        <img src="/<?= $product->thumbnail ?>" class="img-fluid"
                                            alt="<?= htmlspecialchars($product->name) ?>"
                                            style="height: 160px; object-fit: contain;">
                                    </div>
                                </a>
                                <div class="card-body d-flex flex-column p-3">
                                    <a href="/products/detail?slug=<?= $product->slug ?>"
                                        class="text-dark text-decoration-none">
                                        <h6 class="card-title fw-semibold text-truncate mb-2">
                                            <?= htmlspecialchars($product->name) ?></h6>
                                    </a>
                                    <div class="mt-auto">
                                        <span
                                            class="text-danger fw-bold fs-5 d-block mb-2"><?= number_format($product->price) ?>đ</span>
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
                        <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Không tìm thấy sản phẩm phù hợp.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>