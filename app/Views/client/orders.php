<?php
/**
 * View: Lịch sử đơn hàng (Client)
 * @var object|array $user
 * @var \Illuminate\Database\Eloquent\Collection $orders
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

        <!-- Khung danh sách đơn hàng -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-box-open me-2 text-primary"></i>Lịch Sử Đơn Hàng Của Tôi</h5>

                <?php if ($orders->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-box-open text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="text-muted">Bạn chưa có đơn hàng nào</h5>
                        <p class="text-muted mb-4">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi nhé!</p>
                        <a href="/products" class="btn btn-primary rounded-pill px-4 fw-semibold">Mua sắm ngay</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã Đơn</th>
                                    <th>Ngày Đặt</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="fw-semibold">#<?= htmlspecialchars($order->order_code) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                                        <td class="text-danger fw-bold"><?= number_format($order->total_amount, 0, ',', '.') ?> đ</td>
                                        <td>
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
                                        </td>
                                        <td class="text-center">
                                            <a href="/profile/orders/<?= $order->id ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
