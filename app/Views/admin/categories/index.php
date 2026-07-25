<?php

/**
 * View: Quản Lý Danh Mục - Admin
 * @var array $categories
 * @var object|null $editCategory
 */

// 1. Nhúng giao diện chung & Sidebar/Navigation
require_once __DIR__ . '/../../layouts/admin.php';
?>

<!-- NỘI DUNG CHÍNH: QUẢN LÝ DANH MỤC -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề trang -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-layer-group me-2 text-primary"></i> Quản Lý Danh Mục</h4>
        <p class="text-muted small mb-0">Quản lý các nhóm thiết bị gia dụng (như Tủ lạnh, Máy giặt, Điều hòa, V.v.)</p>
    </div>

    <div class="row g-4">
        <!-- BẢNG DANH SÁCH DANH MỤC (BÊN TRÁI) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <!-- Thanh tìm kiếm danh mục -->
                <div class="p-3 border-bottom bg-light">
                    <form action="/admin/categories" method="GET" class="d-flex gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Tìm tên danh mục..."
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary px-3 rounded-3">Tìm</button>
                        <a href="/admin/categories" class="btn btn-sm btn-outline-secondary rounded-3"
                            title="Tải lại"><i class="fa-solid fa-rotate-right"></i></a>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Tên danh mục</th>
                                <th>Slug</th>
                                <th>Số SP</th>
                                <th class="text-end" style="width: 120px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border">#<?= $cat->id ?></span></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($cat->name) ?></div>
                                            <?php if (!empty($cat->description)): ?>
                                                <small class="text-muted text-truncate d-block"
                                                    style="max-width: 200px;"><?= htmlspecialchars($cat->description) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><code class="text-primary small"><?= htmlspecialchars($cat->slug ?? '') ?></code>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info rounded-pill px-2">
                                                <?= $cat->products_count ?? 0 ?> SP
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Sửa"
                                                onclick='fillEditForm(<?= json_encode($cat) ?>)'>
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="/admin/categories/delete?id=<?= $cat->id ?>"
                                                class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa danh mục này? Các sản phẩm thuộc danh mục có thể bị ảnh hưởng!')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i> Chưa có danh mục nào.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM THÊM / SỬA DANH MỤC (BÊN PHẢI) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white position-sticky" style="top: 20px;">
                <h5 class="fw-bold mb-3" id="formTitle">
                    <i class="fa-solid fa-plus-circle me-2 text-primary"></i>Thêm Danh Mục Mới
                </h5>

                <form action="/admin/categories/save" method="POST" id="categoryForm">
                    <?= \App\Helpers\CsrfHelper::csrfField() ?>
                    <input type="hidden" name="id" id="catId">

                    <!-- Tên danh mục -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tên danh mục <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="catName" class="form-control rounded-3"
                            placeholder="VD: Tủ Lạnh & Tủ Đông" required onkeyup="generateSlug(this.value)">
                    </div>

                    <!-- Đường dẫn Slug -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Slug (Tự động tạo)</label>
                        <input type="text" name="slug" id="catSlug" class="form-control rounded-3 bg-light"
                            placeholder="tu-lanh-tu-dong" readonly>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Mô tả danh mục</label>
                        <textarea name="description" id="catDescription" class="form-control rounded-3" rows="3"
                            placeholder="Nhập mô tả ngắn về danh mục này..."></textarea>
                    </div>

                    <!-- Nút bấm -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill fw-semibold flex-grow-1 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Danh Mục
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-3" onclick="resetCategoryForm()">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm tự động tạo Slug từ tên danh mục
    function generateSlug(text) {
        let slug = text.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Bỏ dấu tiếng Việt
            .replace(/[đĐ]/g, 'd')
            .replace(/([^0-9a-z-\s])/g, '')
            .replace(/(\s+)/g, '-')
            .replace(/^-+/g, '')
            .replace(/-+$/g, '');
        document.getElementById('catSlug').value = slug;
    }

    // Đổ dữ liệu vào Form bên phải khi bấm sửa
    function fillEditForm(category) {
        document.getElementById('catId').value = category.id;
        document.getElementById('catName').value = category.name;
        document.getElementById('catSlug').value = category.slug || '';
        document.getElementById('catDescription').value = category.description || '';

        document.getElementById('formTitle').innerHTML =
            '<i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Sửa Danh Mục #' + category.id;
    }

    // Reset form về trạng thái Thêm Mới
    function resetCategoryForm() {
        document.getElementById('categoryForm').reset();
        document.getElementById('catId').value = '';
        document.getElementById('formTitle').innerHTML =
            '<i class="fa-solid fa-plus-circle me-2 text-primary"></i>Thêm Danh Mục Mới';
    }
</script>