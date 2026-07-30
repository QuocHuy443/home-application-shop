<?php

/**
 * View: Quản Lý Người Dùng & Phân Quyền - Admin
 * @var array $users
 * @var array $roles
 * @var array $filters
 */

use App\Helpers\SessionHelper;

$currentUser = SessionHelper::user();

require_once __DIR__ . '/../../layouts/admin.php';
?>

<!-- NỘI DUNG CHÍNH: QUẢN LÝ NGƯỜI DÙNG -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề trang -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-users me-2 text-primary"></i> Quản Lý Người Dùng</h4>
            <p class="text-muted small mb-0">Quản lý tài khoản khách hàng, phân quyền hệ thống và trạng thái hoạt động.</p>
        </div>
    </div>

    <!-- BỘ LỌC VÀ TÌM KIẾM -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="/admin/users" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0"
                        placeholder="Tìm tên, email hoặc số điện thoại..."
                        value="<?= htmlspecialchars($filters['keyword'] ?? $_GET['keyword'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả vai trò --</option>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r->id ?>" <?= (string)($filters['role_id'] ?? $_GET['role_id'] ?? '') === (string)$r->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($r->name)) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1" <?= (string)($filters['role_id'] ?? $_GET['role_id'] ?? '') === '1' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                        <option value="2" <?= (string)($filters['role_id'] ?? $_GET['role_id'] ?? '') === '2' ? 'selected' : '' ?>>Khách hàng (Customer)</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <?php $currentStatus = (string)($filters['status'] ?? $_GET['status'] ?? ''); ?>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" <?= $currentStatus === '1' ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="0" <?= $currentStatus === '0' ? 'selected' : '' ?>>Đã khóa</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3">Lọc</button>
                <a href="/admin/users" class="btn btn-sm btn-outline-secondary rounded-3" title="Tải lại"><i class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- BẢNG DANH SÁCH NGƯỜI DÙNG -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 220px;">Họ và tên</th>
                        <th>Thông tin liên hệ</th>
                        <th style="width: 150px;">Vai trò (Phân quyền)</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th class="text-center" style="width: 160px;">Khóa / Mở tài khoản</th>
                        <th class="text-end" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users) && count($users) > 0): foreach ($users as $u): 
                        $roleName = $u->role->name ?? 'customer';
                        $currentUserId = $currentUser ? (is_array($currentUser) ? ($currentUser['id'] ?? null) : ($currentUser->id ?? null)) : null;
                        $isSelf = ($currentUserId && (int)$u->id === (int)$currentUserId);
                    ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border">#<?= $u->id ?></span></td>
                                <td>
                                    <div class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($u->name ?? '') ?>"><?= htmlspecialchars($u->name ?? 'N/A') ?></div>
                                    <small class="text-muted">Tham gia: <?= date('d/m/Y', strtotime($u->created_at ?? 'now')) ?></small>
                                </td>
                                <td>
                                    <div class="small text-truncate"><i class="fa-solid fa-envelope me-1 text-muted"></i><?= htmlspecialchars($u->email ?? '') ?></div>
                                    <div class="small text-truncate"><i class="fa-solid fa-phone me-1 text-muted"></i><?= htmlspecialchars($u->phone ?? 'Chưa cập nhật') ?></div>
                                </td>
                                <td>
                                    <?php if ($roleName === 'admin'): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                            <i class="fa-solid fa-user-shield me-1"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                            <i class="fa-solid fa-user me-1"></i> Customer
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($u->status ?? 1) == 1): ?>
                                        <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check me-1"></i>Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger"><i class="fa-solid fa-lock me-1"></i>Đã khóa</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($isSelf): ?>
                                        <span class="text-muted small" style="font-size: 0.85rem;" title="Bạn không thể tự khóa tài khoản của chính mình">
                                            <i class="fa-solid fa-shield-halved text-primary me-1"></i> Bản thân
                                        </span>
                                    <?php else: ?>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                                                <?= ($u->status ?? 1) == 1 ? 'checked' : '' ?>
                                                onchange="toggleUserStatus(<?= $u->id ?>, this.checked, event)"
                                                title="<?= ($u->status ?? 1) == 1 ? 'Nhấn để khóa tài khoản' : 'Nhấn để mở khóa' ?>">
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle"
                                        title="Phân quyền / Sửa thông tin" 
                                        onclick='openRoleModal(<?= json_encode([
                                            'id' => $u->id,
                                            'name' => $u->name,
                                            'email' => $u->email,
                                            'role' => $roleName
                                        ]) ?>)'>
                                        <i class="fa-solid fa-user-gear"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fa-3x mb-3 d-block"></i> Không tìm thấy người dùng nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- PHÂN TRANG -->
        <?php if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages()): ?>
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị từ <?= $users->firstItem() ?> đến <?= $users->lastItem() ?> trong tổng số <?= $users->total() ?> người dùng
                </div>
                <div>
                    <?= \App\Helpers\PaginationHelper::render($users) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL CẬP NHẬT PHÂN QUYỀN VÀ THÔNG TIN -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="/admin/users/update-role" method="POST">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
                
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="roleModalLabel"><i class="fa-solid fa-user-gear text-primary me-2"></i>Phân Quyền Tài Khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="user_id" id="modalUserId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Họ và tên</label>
                        <input type="text" id="modalUserName" class="form-control rounded-3 bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <input type="text" id="modalUserEmail" class="form-control rounded-3 bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Vai trò / Phân quyền <span class="text-danger">*</span></label>
                        <select name="role" id="modalUserRole" class="form-select rounded-3" required>
                            <option value="customer">Khách hàng (Customer)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">* Lưu ý: Quyền Admin có toàn quyền quản trị hệ thống gia dụng này.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FORM ẨN ĐỂ SUBMIT KHÓA / MỞ KHÓA TÀI KHOẢN -->
<form id="toggleStatusForm" action="/admin/users/toggle-status" method="POST" style="display: none;">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
    
    
    <input type="hidden" name="id" id="toggleUserId">
    <input type="hidden" name="status" id="toggleUserStatus">
</form>

<script>
    function openRoleModal(user) {
        document.getElementById('modalUserId').value = user.id;
        document.getElementById('modalUserName').value = user.name || '';
        document.getElementById('modalUserEmail').value = user.email || '';
        document.getElementById('modalUserRole').value = user.role || 'customer';

        var modal = new bootstrap.Modal(document.getElementById('roleModal'));
        modal.show();
    }

    function toggleUserStatus(userId, isChecked, event) {
        const status = isChecked ? 1 : 0;
        const actionText = isChecked ? 'mở khóa' : 'khóa';

        if (confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản này không?`)) {
            document.getElementById('toggleUserId').value = userId;
            document.getElementById('toggleUserStatus').value = status;
            document.getElementById('toggleStatusForm').submit();
        } else {
            if (event && event.target) {
                event.target.checked = !isChecked;
            }
        }
    }
</script>