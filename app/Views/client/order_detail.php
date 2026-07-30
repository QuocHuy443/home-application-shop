<?php
/**
 * View: Chi tiết đơn hàng (Client)
 * @var object|array $user
 * @var \App\Models\Order $order
 */

require_once __DIR__ . '/../layouts/main.php';

// Trích xuất thông tin người dùng an toàn
$displayName   = is_array($user) ? ($user['name'] ?? '') : ($user->name ?? '');
$displayEmail  = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar tài khoản -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="text-center p-3 border-bottom">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto fs-2 fw-bold mb-3" style="width: 70px; height: 70px;">
                        <?= strtoupper(substr(!empty($displayName) ? $displayName : ($displayEmail ?? 'U'), 0, 1)) ?>
                    </div>
                    <h6 class="fw-bold text-dark mb-1"><?= !empty($displayName) ? htmlspecialchars($displayName) : 'Thành viên' ?></h6>
                    <small class="text-muted d-block text-truncate"><?= htmlspecialchars($displayEmail) ?></small>
                </div>

                <div class="list-group list-group-flush mt-3 border-0">
                    <a href="/profile" class="list-group-item list-group-item-action border-0 rounded-3 fw-semibold my-1 text-dark">
                        <i class="fa-solid fa-user me-2"></i> Thông tin tài khoản
                    </a>
                    <a href="/profile/orders" class="list-group-item list-group-item-action border-0 rounded-3 active fw-semibold my-1">
                        <i class="fa-solid fa-box-open me-2"></i> Lịch sử đơn hàng
                    </a>
                    <a href="/logout" class="list-group-item list-group-item-action border-0 rounded-3 text-danger my-1">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Khung chi tiết đơn hàng -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-file-invoice-dollar me-2 text-primary"></i>Chi Tiết Đơn Hàng #<?= htmlspecialchars($order->order_code) ?></h5>
                    <a href="/profile/orders" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0 rounded-3 p-3 h-100">
                            <h6 class="fw-bold mb-3 text-dark">Thông Tin Giao Hàng</h6>
                            <p class="mb-1"><span class="text-muted">Người nhận:</span> <span class="fw-semibold"><?= htmlspecialchars($order->shipping_name) ?></span></p>
                            <p class="mb-1"><span class="text-muted">Điện thoại:</span> <?= htmlspecialchars($order->shipping_phone) ?></p>
                            <p class="mb-1"><span class="text-muted">Địa chỉ:</span> <?= htmlspecialchars($order->shipping_address) ?></p>
                            <?php if (!empty($order->note)): ?>
                                <p class="mb-0"><span class="text-muted">Ghi chú:</span> <?= htmlspecialchars($order->note) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0 rounded-3 p-3 h-100">
                            <h6 class="fw-bold mb-3 text-dark">Thông Tin Đơn Hàng</h6>
                            <p class="mb-1"><span class="text-muted">Ngày đặt:</span> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
                            <p class="mb-1">
                                <span class="text-muted">Trạng thái:</span>
                                <?php if ($order->status == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                <?php elseif ($order->status == 'processing'): ?>
                                    <span class="badge bg-info text-dark">Đang chuẩn bị</span>
                                <?php elseif ($order->status == 'shipped'): ?>
                                    <span class="badge bg-primary">Đang giao</span>
                                <?php elseif ($order->status == 'completed'): ?>
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                <?php elseif ($order->status == 'cancelled'): ?>
                                    <span class="badge bg-danger">Đã hủy</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($order->status) ?></span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-0 text-danger fw-bold fs-5 mt-2">Tổng Tiền: <?= number_format($order->total_amount, 0, ',', '.') ?> đ</p>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-dark">Sản Phẩm Đã Đặt</h6>
                <div class="table-responsive">
                    <table class="table align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Sản Phẩm</th>
                                <th class="text-center">Đơn Giá</th>
                                <th class="text-center">Số Lượng</th>
                                <th class="text-end">Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item->product && $item->product->thumbnail): ?>
                                                <img src="<?= htmlspecialchars($item->product->thumbnail) ?>" alt="<?= htmlspecialchars($item->product->name) ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            <?php else: ?>
                                                <div class="bg-secondary rounded d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; margin-right: 15px;">
                                                    <i class="fa-solid fa-image text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($item->product ? $item->product->name : 'Sản phẩm đã xóa') ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?= number_format($item->price, 0, ',', '.') ?> đ</td>
                                    <td class="text-center"><?= $item->quantity ?></td>
                                    <td class="text-end fw-semibold text-danger"><?= number_format($item->price * $item->quantity, 0, ',', '.') ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
