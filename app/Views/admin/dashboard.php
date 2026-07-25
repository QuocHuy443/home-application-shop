<div class="container-fluid py-4">
    <h2 class="mb-4">Bảng Điều Khiển</h2>
    
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h5>Tổng Sản Phẩm</h5>
                    <h3 class="fw-bold"><?= $totalProducts ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <h5>Tổng Đơn Hàng</h5>
                    <h3 class="fw-bold"><?= $totalOrders ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm border-0">
                <div class="card-body">
                    <h5>Khách Hàng</h5>
                    <h3 class="fw-bold"><?= $totalUsers ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm border-0">
                <div class="card-body">
                    <h5>Doanh Thu</h5>
                    <h3 class="fw-bold"><?= number_format($revenue ?? 0) ?> đ</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            Đơn Hàng Gần Đây
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recentOrders)): ?>
                        <?php foreach($recentOrders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order->order_code) ?></td>
                                <td><?= htmlspecialchars($order->shipping_name) ?></td>
                                <td><?= number_format($order->total_amount) ?> đ</td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($order->status) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Chưa có đơn hàng nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
