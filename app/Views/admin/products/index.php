<?php

/**
 * View: Quản Lý Sản Phẩm - Admin
 * @var array $products
 * @var array $categories
 */

// 1. Nhúng giao diện chung & Sidebar/Navigation đã viết riêng
require_once __DIR__ . '/../../layouts/admin.php';
?>

<!-- NỘI DUNG CHÍNH: QUẢN LÝ SẢN PHẨM -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề & Nút hành động -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-boxes-stacked me-2 text-primary"></i> Quản Lý Sản Phẩm</h4>
            <p class="text-muted small mb-0">Xem, tìm kiếm và cập nhật danh sách thiết bị gia dụng trong hệ thống.</p>
        </div>
        <button class="btn btn-primary rounded-pill fw-semibold px-3 shadow-sm" data-bs-toggle="modal"
            data-bs-target="#productModal" onclick="openCreateModal()">
            <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm mới
        </button>
    </div>

    <!-- BỘ LỌC VÀ TÌM KIẾM -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="/admin/products" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Tìm theo tên hoặc mã SP..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= ($_GET['category_id'] ?? '') == $cat->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->name) ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái kho --</option>
                    <option value="in_stock" <?= ($_GET['stock_status'] ?? '') == 'in_stock' ? 'selected' : '' ?>>Còn
                        hàng (> 0)</option>
                    <option value="out_of_stock"
                        <?= ($_GET['stock_status'] ?? '') == 'out_of_stock' ? 'selected' : '' ?>>Hết hàng (= 0)</option>
                    <option value="low_stock" <?= ($_GET['stock_status'] ?? '') == 'low_stock' ? 'selected' : '' ?>>Sắp
                        hết hàng (<= 5)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3">Lọc</button>
                <a href="/admin/products" class="btn btn-sm btn-outline-secondary rounded-3" title="Tải lại"><i
                        class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- BẢNG DANH SÁCH SẢN PHẨM -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Ảnh</th>
                        <th>Mã / Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Đơn giá</th>
                        <th>Tồn kho</th>
                        <th>Kích hoạt</th>
                        <th class="text-end" style="width: 130px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <img src="/<?= $p->thumbnail ?? 'assets/images/placeholder.jpg' ?>" class="rounded-3 border"
                                        style="width: 48px; height: 48px; object-fit: contain; background: #fff;"
                                        alt="Thumbnail">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border mb-1">#<?= $p->id ?></span>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                        <?= htmlspecialchars($p->name) ?></div>
                                </td>
                                <td><span
                                        class="badge bg-info-subtle text-info px-2 py-1"><?= htmlspecialchars($p->category_name ?? 'Chưa phân loại') ?></span>
                                </td>
                                <td class="fw-bold text-danger"><?= number_format($p->price) ?>đ</td>
                                <td>
                                    <?php if ($p->stock == 0): ?>
                                        <span class="badge bg-danger-subtle text-danger"><i
                                                class="fa-solid fa-circle-exclamation me-1"></i>Hết hàng (0)</span>
                                    <?php elseif ($p->stock <= 5): ?>
                                        <span class="badge bg-warning-subtle text-warning text-dark"><i
                                                class="fa-solid fa-triangle-exclamation me-1"></i>Sắp hết (<?= $p->stock ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success"><?= $p->stock ?> sản phẩm</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            <?= ($p->status ?? 1) == 1 ? 'checked' : '' ?> title="Ẩn/Hiện sản phẩm">
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Sửa"
                                        onclick='openEditModal(<?= json_encode($p) ?>)'>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="/admin/products/delete?id=<?= $p->id ?>"
                                        class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fa-3x mb-3 d-block"></i> Chưa có dữ liệu sản phẩm nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FORM THÊM / SỬA SẢN PHẨM -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="/admin/products/save" method="POST" enctype="multipart/form-data" id="productForm">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="productModalLabel"><i
                            class="fa-solid fa-box text-primary me-2"></i>Thêm Sản Phẩm Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="productId">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Tên sản phẩm <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="productName" class="form-control rounded-3"
                                placeholder="Nhập tên thiết bị gia dụng..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Danh mục <span
                                    class="text-danger">*</span></label>
                            <select name="category_id" id="productCategory" class="form-select rounded-3" required>
                                <option value="">Chọn danh mục...</option>
                                <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                                <?php endforeach;
                                endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Giá bán (VNĐ) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="price" id="productPrice" class="form-control rounded-3"
                                placeholder="VD: 1500000" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Số lượng tồn kho <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="stock" id="productStock" class="form-control rounded-3"
                                placeholder="VD: 50" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Hình ảnh đại diện</label>
                            <input type="file" name="thumbnail" id="productImage" class="form-control rounded-3"
                                accept="image/*" onchange="previewImage(this)">
                            <div class="mt-2 text-center border rounded-3 p-2 bg-light d-none" id="previewContainer">
                                <img id="imagePreview" src="" class="img-fluid rounded-2" style="max-height: 100px;">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Mô tả sản phẩm</label>
                            <textarea name="description" id="productDescription" class="form-control rounded-3" rows="3"
                                placeholder="Mô tả thông số, công suất, tính năng nổi bật..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold"><i
                            class="fa-solid fa-floppy-disk me-1"></i> Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('productModalLabel').innerHTML =
            '<i class="fa-solid fa-box text-primary me-2"></i>Thêm Sản Phẩm Mới';
        document.getElementById('previewContainer').classList.add('d-none');
    }

    function openEditModal(product) {
        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.name;
        document.getElementById('productCategory').value = product.category_id;
        document.getElementById('productPrice').value = product.price;
        document.getElementById('productStock').value = product.stock;
        document.getElementById('productDescription').value = product.description || '';

        document.getElementById('productModalLabel').innerHTML =
            '<i class="fa-solid fa-pen-to-square text-primary me-2"></i>Sửa Sản Phẩm #' + product.id;

        if (product.thumbnail) {
            document.getElementById('imagePreview').src = '/' + product.thumbnail;
            document.getElementById('previewContainer').classList.remove('d-none');
        } else {
            document.getElementById('previewContainer').classList.add('d-none');
        }

        var modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>