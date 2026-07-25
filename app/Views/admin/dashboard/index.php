<?php

/**
 * View: Dashboard (Tổng Quan) - Admin
 * @var float $totalRevenue
 * @var int $newOrdersCount
 * @var int $newUsersCount
 * @var int $totalProducts
 * @var array $topProducts
 * @var array $recentUsers
 * @var array $monthlyRevenue
 */

// 1. Nhúng giao diện chung & Sidebar/Navigation
require_once __DIR__ . '/../../layouts/admin.php';
?>

<!-- NỘI DUNG CHÍNH: DASHBOARD TỔNG QUAN -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề trang -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-chart-pie me-2 text-primary"></i> Tổng Quan Hệ Thống</h4>
            <p class="text-muted small mb-0">Theo dõi chỉ số kinh doanh và hoạt động của cửa hàng thiết bị gia dụng.</p>
        </div>
        <div class="text-muted small">
            <i class="fa-regular fa-calendar me-1"></i> Hôm nay: <strong><?= date('d/m/Y') ?></strong>
        </div>
    </div>

    <!-- 1. THẺ THỐNG KÊ NHANH (KPI CARDS) -->
    <div class="row g-3 mb-4">
        <!-- Doanh thu -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Doanh Thu Thống Kê</small>
                        <h4 class="fw-bold text-primary mb-0 mt-1"><?= number_format($totalRevenue ?? 0) ?>đ</h4>
                    </div>
                    <div class="bg-primary-subtle p-3 rounded-circle text-primary">
                        <i class="fa-solid fa-sack-dollar fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn hàng mới -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Đơn Hàng Mới</small>
                        <h4 class="fw-bold text-warning mb-0 mt-1"><?= number_format($newOrdersCount ?? 0) ?></h4>
                    </div>
                    <div class="bg-warning-subtle p-3 rounded-circle text-warning">
                        <i class="fa-solid fa-cart-flatbed fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Khách hàng mới -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Người Dùng Mới</small>
                        <h4 class="fw-bold text-success mb-0 mt-1"><?= number_format($newUsersCount ?? 0) ?></h4>
                    </div>
                    <div class="bg-success-subtle p-3 rounded-circle text-success">
                        <i class="fa-solid fa-user-plus fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng sản phẩm -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Sản Phẩm Đang Bán</small>
                        <h4 class="fw-bold text-info mb-0 mt-1"><?= number_format($totalProducts ?? 0) ?></h4>
                    </div>
                    <div class="bg-info-subtle p-3 rounded-circle text-info">
                        <i class="fa-solid fa-boxes-packing fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. BIỂU ĐỒ DOANH THU THEO THÁNG -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Biểu Đồ Doanh
                Thu Theo Tháng</h6>
            <a href="/admin/reports" class="btn btn-sm btn-outline-primary rounded-pill">Xem báo cáo chi tiết <i
                    class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div style="height: 280px;">
            <canvas id="dashboardRevenueChart"></canvas>
        </div>
    </div>

    <!-- 3. BẢNG SẢN PHẨM BÁN CHẠY VÀ NGƯỜI DÙNG MỚI -->
    <div class="row g-4">

        <!-- Bảng top sản phẩm bán chạy -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-fire me-2 text-danger"></i>Sản Phẩm Bán
                        Chạy Nhất</h6>
                    <a href="/admin/products" class="text-primary small text-decoration-none">Quản lý SP</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đã bán</th>
                                <th class="text-end">Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topProducts)): foreach ($topProducts as $p): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="/<?= $p->thumbnail ?? 'assets/images/placeholder.jpg' ?>"
                                                    class="rounded-3 border"
                                                    style="width: 40px; height: 40px; object-fit: contain; background: #fff;"
                                                    alt="Thumb">
                                                <div class="fw-semibold text-dark text-truncate" style="max-width: 220px;">
                                                    <?= htmlspecialchars($p->name) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1"><i
                                                    class="fa-solid fa-bolt me-1"></i><?= $p->sold_count ?> SP</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark"><?= number_format($p->price) ?>đ</td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Chưa có dữ liệu bán hàng.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bảng người dùng mới đăng ký -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-user-clock me-2 text-success"></i>Người
                        Dùng Mới Nhất</h6>
                    <a href="/admin/users" class="text-primary small text-decoration-none">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Khách hàng</th>
                                <th class="text-end">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentUsers)): foreach ($recentUsers as $u): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark"><?= htmlspecialchars($u->fullname) ?></div>
                                            <small class="text-muted d-block"><?= htmlspecialchars($u->email) ?></small>
                                        </td>
                                        <td class="text-end small text-muted">
                                            <?= date('d/m/Y', strtotime($u->created_at ?? 'now')) ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">Chưa có người dùng mới.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu doanh thu tháng từ backend PHP
    const monthlyLabels = <?= json_encode(array_column($monthlyRevenue ?? [], 'month')) ?>;
    const monthlyData = <?= json_encode(array_column($monthlyRevenue ?? [], 'revenue')) ?>;

    const ctxDashboard = document.getElementById('dashboardRevenueChart').getContext('2d');
    new Chart(ctxDashboard, {
        type: 'bar',
        data: {
            labels: monthlyLabels.length ? monthlyLabels : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5',
                'Tháng 6'
            ],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: monthlyData.length ? monthlyData : [12000000, 19000000, 15000000, 25000000, 22000000,
                    30000000
                ],
                backgroundColor: '#0d6efd',
                borderRadius: 6,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000) + ' Tr';
                        }
                    }
                }
            }
        }
    });
</script>

<?php
// 2. Nhúng footer chung
require_once __DIR__ . '/../layouts/footer.php';
?>