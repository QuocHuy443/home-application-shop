<?php

/**
 * View: Quản Lý Đơn Hàng - Admin
 * @var array $orders
 */

// 1. Nhúng giao diện chung & Sidebar/Navigation
require_once __DIR__ . '/../../layouts/admin.php';
?>

<!-- NỘI DUNG CHÍNH: QUẢN LÝ ĐƠN HÀNG -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề trang -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-receipt me-2 text-primary"></i> Quản Lý Đơn Hàng</h4>
            <p class="text-muted small mb-0">Theo dõi, duyệt và cập nhật tiến độ giao hàng thiết bị gia dụng.</p>
        </div>
    </div>

    <!-- BỘ LỌC VÀ TÌM KIẾM -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="/admin/orders" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Tìm tên, SĐT khách hàng hoặc Mã ĐH..."
                        value="<?= htmlspecialchars((string)($_GET['search'] ?? '')) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Chờ duyệt
                    </option>
                    <option value="shipping" <?= ($_GET['status'] ?? '') == 'shipping' ? 'selected' : '' ?>>Đang giao
                    </option>
                    <option value="completed" <?= ($_GET['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Đã hoàn
                        thành</option>
                    <option value="cancelled" <?= ($_GET['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Đã hủy
                    </option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3">Lọc đơn hàng</button>
                <a href="/admin/orders" class="btn btn-sm btn-outline-secondary rounded-3" title="Tải lại"><i
                        class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- BẢNG DANH SÁCH ĐƠN HÀNG -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-end" style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): foreach ($orders as $o): ?>
                    <tr>
                        <td><span class="badge bg-light text-dark border fw-bold">#<?= $o->id ?></span></td>
                        <td>
                            <div class="fw-bold text-dark">
                                <?= htmlspecialchars((string)($o->shipping_name ?? $o->fullname ?? $o->user->name ?? 'Khách hàng')) ?>
                            </div>
                            <small class="text-muted">
                                <i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars((string)($o->shipping_phone ?? $o->phone ?? $o->user->phone ?? 'Chưa có SĐT')) ?>
                            </small>
                        </td>
                        <td class="small text-muted"><?= date('H:i d/m/Y', strtotime($o->created_at ?? 'now')) ?></td>
                        <td class="fw-bold text-danger"><?= number_format($o->total_amount ?? 0) ?>đ</td>
                        <td>
                            <span class="badge bg-light text-secondary border">
                                <?= ($o->payment_method ?? '') == 'cod' ? 'COD' : 'Chuyển khoản' ?>
                            </span>
                        </td>
                        <td>
                            <?php if (($o->status ?? '') == 'pending'): ?>
                            <span class="badge bg-warning-subtle text-warning text-dark"><i
                                    class="fa-solid fa-clock me-1"></i>Chờ duyệt</span>
                            <?php elseif (($o->status ?? '') == 'shipping'): ?>
                            <span class="badge bg-info-subtle text-info"><i class="fa-solid fa-truck me-1"></i>Đang
                                giao</span>
                            <?php elseif (($o->status ?? '') == 'completed'): ?>
                            <span class="badge bg-success-subtle text-success"><i
                                    class="fa-solid fa-circle-check me-1"></i>Hoàn thành</span>
                            <?php elseif (($o->status ?? '') == 'cancelled'): ?>
                            <span class="badge bg-danger-subtle text-danger"><i
                                    class="fa-solid fa-circle-xmark me-1"></i>Đã hủy</span>
                            <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary">Không xác định</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-circle"
                                title="Xem chi tiết & Cập nhật" onclick='openOrderDetailModal(<?= json_encode($o) ?>)'>
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach;
                    else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fa-3x mb-3 d-block"></i> Chưa có đơn hàng nào trong danh sách.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL XEM CHI TIẾT VÀ CẬP NHẬT TRẠNG THÁI -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="orderModalLabel"><i
                        class="fa-solid fa-file-invoice text-primary me-2"></i>Chi Tiết Đơn Hàng #<span
                        id="modalOrderId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="/admin/orders/update-status" method="POST">
                <?= \App\Helpers\CsrfHelper::csrfField() ?>
                
                <div class="modal-body p-4 pt-0">
                    <input type="hidden" name="order_id" id="modalInputOrderId">

                    <!-- THÔNG TIN NGƯỜI NHẬN & CẬP NHẬT TRẠNG THÁI -->
                    <div class="row g-3 mb-4 bg-light p-3 rounded-4">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-user me-2 text-primary"></i>Thông
                                tin nhận hàng</h6>
                            <p class="mb-1 small"><strong>Người nhận:</strong> <span id="modalCustomerName"></span></p>
                            <p class="mb-1 small"><strong>Số điện thoại:</strong> <span id="modalCustomerPhone"></span>
                            </p>
                            <p class="mb-1 small"><strong>Địa chỉ:</strong> <span id="modalCustomerAddress"></span></p>
                            <p class="mb-0 small text-muted"><strong>Ghi chú:</strong> <span
                                    id="modalCustomerNote">Khong co</span></p>
                        </div>

                        <!-- CẬP NHẬT TRẠNG THÁI -->
                        <div class="col-md-5 border-start">
                            <h6 class="fw-bold text-dark mb-2"><i
                                    class="fa-solid fa-sliders me-2 text-primary"></i>Trạng thái đơn hàng</h6>
                            <label class="form-label small fw-semibold">Cập nhật tiến độ:</label>
                            <select name="status" id="modalOrderStatus" class="form-select form-select-sm rounded-3">
                                <option value="pending">Chờ duyệt</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="cancelled">Đã hủy đơn</option>
                            </select>
                        </div>
                    </div>

                    <!-- DANH SÁCH SẢN PHẨM ĐẶT MUA -->
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-box-open me-2 text-primary"></i>Sản phẩm đặt mua</h6>
                    <div class="table-responsive mb-3 border rounded-3">
                        <table class="table align-middle table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="modalOrderItems">
                                <!-- Render danh sách SP bằng JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- TỔNG TIỀN -->
                    <div
                        class="d-flex justify-content-between align-items-center p-3 bg-primary-subtle text-primary rounded-3">
                        <span class="fw-bold">Tổng thanh toán:</span>
                        <span class="fw-bold fs-5" id="modalTotalAmount">0đ</span>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold"><i
                            class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Đơn Hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openOrderDetailModal(order) {
    document.getElementById('modalOrderId').innerText = order.id || '';
    document.getElementById('modalInputOrderId').value = order.id || '';
    document.getElementById('modalCustomerName').innerText = order.shipping_name || order.fullname || (order.user ? order.user.name : '') || 'Chưa cập nhật';
    document.getElementById('modalCustomerPhone').innerText = order.shipping_phone || order.phone || (order.user ? order.user.phone : '') || 'Chưa có SĐT';
    document.getElementById('modalCustomerAddress').innerText = order.shipping_address || order.address || 'Chưa có địa chỉ';
    document.getElementById('modalCustomerNote').innerText = order.note || 'Không có';
    document.getElementById('modalOrderStatus').value = order.status || 'pending';
    document.getElementById('modalTotalAmount').innerText = new Intl.NumberFormat('vi-VN').format(order.total_amount || 0) + 'đ';

    // Build bảng sản phẩm đặt mua
    let itemsHtml = '';
    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            let itemTotal = (item.price || 0) * (item.quantity || 0);
            itemsHtml += `
                <tr>
                    <td class="fw-semibold">${item.product_name || (item.product ? item.product.name : 'Sản phẩm')}</td>
                    <td class="text-center">${new Intl.NumberFormat('vi-VN').format(item.price || 0)}đ</td>
                    <td class="text-center">${item.quantity || 0}</td>
                    <td class="text-end fw-bold text-danger">${new Intl.NumberFormat('vi-VN').format(itemTotal)}đ</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = '<tr><td colspan="4" class="text-center text-muted py-3">Không tìm thấy chi tiết sản phẩm.</td></tr>';
    }

    document.getElementById('modalOrderItems').innerHTML = itemsHtml;

    var modal = new bootstrap.Modal(document.getElementById('orderModal'));
    modal.show();
}
</script>