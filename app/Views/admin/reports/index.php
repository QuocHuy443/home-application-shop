<?php

/**
 * View: Báo Cáo & Thống Kê Doanh Thu - Admin
 * @var array $reportData
 * @var float $totalRevenue
 * @var int $totalOrders
 * @var string $startDate
 * @var string $endDate
 */

// 1. Nhúng giao diện chung & Sidebar/Navigation đã viết
require_once __DIR__ . '/../../layouts/admin.php';

// Mặc định ngày nếu chưa chọn (Ví dụ: Đầu tháng đến hiện tại)
$fromDate = $_GET['start_date'] ?? date('Y-m-01');
$toDate = $_GET['end_date'] ?? date('Y-m-d');
?>

<!-- NỘI DUNG CHÍNH: BÁO CÁO THỐNG KÊ -->
<div class="content-wrapper p-4">

    <!-- Tiêu đề trang & Nút xuất báo cáo -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Báo Cáo & Thống Kê Doanh
                Thu</h4>
            <p class="text-muted small mb-0">Theo dõi chi tiết hiệu quả kinh doanh cửa hàng thiết bị gia dụng theo thời
                gian.</p>
        </div>

        <!-- Nút Xuất File Báo Cáo -->
        <div class="d-flex gap-2">
            <a href="/admin/reports/export-excel?start_date=<?= $fromDate ?>&end_date=<?= $toDate ?>"
                class="btn btn-success rounded-pill fw-semibold px-3 shadow-sm">
                <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
            </a>
            <a href="/admin/reports/export-pdf?start_date=<?= $fromDate ?>&end_date=<?= $toDate ?>"
                class="btn btn-outline-danger rounded-pill fw-semibold px-3 shadow-sm" target="_blank">
                <i class="fa-solid fa-file-pdf me-1"></i> Xuất PDF
            </a>
        </div>
    </div>

    <!-- BỘ LỌC KHOẢNG THỜI GIAN (TỪ NGÀY - ĐẾN NGÀY) -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="/admin/reports" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4 col-sm-6">
                <label class="form-label fw-semibold small text-muted mb-1"><i
                        class="fa-regular fa-calendar-check me-1"></i>Từ ngày</label>
                <input type="date" name="start_date" class="form-control rounded-3"
                    value="<?= htmlspecialchars($fromDate) ?>" required>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label fw-semibold small text-muted mb-1"><i
                        class="fa-regular fa-calendar-xmark me-1"></i>Đến ngày</label>
                <input type="date" name="end_date" class="form-control rounded-3"
                    value="<?= htmlspecialchars($toDate) ?>" required>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-3 w-100 fw-semibold">
                    <i class="fa-solid fa-filter me-1"></i> Lọc dữ liệu
                </button>
                <a href="/admin/reports" class="btn btn-outline-secondary rounded-3" title="Đặt lại"><i
                        class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- TỔNG QUAN CHỈ SỐ (KPI CARDS) -->
    <div class="row g-3 mb-4">
        <!-- Tổng doanh thu -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Tổng Doanh Thu</small>
                        <h3 class="fw-bold text-primary mb-0 mt-1"><?= number_format($totalRevenue ?? 0) ?>đ</h3>
                    </div>
                    <div class="bg-primary-subtle p-3 rounded-circle text-primary">
                        <i class="fa-solid fa-coins fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số đơn hàng -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Đơn Hàng Hoàn Thành</small>
                        <h3 class="fw-bold text-success mb-0 mt-1"><?= number_format($totalOrders ?? 0) ?> đơn</h3>
                    </div>
                    <div class="bg-success-subtle p-3 rounded-circle text-success">
                        <i class="fa-solid fa-cart-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Giá trị trung bình/đơn -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold text-uppercase">Giá Trị Đơn Trung Bình</small>
                        <h3 class="fw-bold text-info mb-0 mt-1">
                            <?= ($totalOrders ?? 0) > 0 ? number_format($totalRevenue / $totalOrders) : 0 ?>đ
                        </h3>
                    </div>
                    <div class="bg-info-subtle p-3 rounded-circle text-info">
                        <i class="fa-solid fa-chart-pie fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BIỂU ĐỒ DOANH THU -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-chart-area me-2 text-primary"></i>Biểu Đồ Biến Động
            Doanh Thu</h5>
        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- BẢNG CHI TIẾT DOANH THU THEO NGÀY -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="p-3 border-bottom bg-light">
            <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-list me-2 text-primary"></i>Chi Tiết Doanh Thu Theo
                Ngày</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ngày ghi nhận</th>
                        <th class="text-center">Số đơn hàng</th>
                        <th class="text-end">Doanh thu ngày</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportData)): foreach ($reportData as $row): ?>
                            <tr>
                                <td class="fw-semibold text-dark"><?= date('d/m/Y', strtotime($row->date)) ?></td>
                                <td class="text-center"><span
                                        class="badge bg-light text-dark border px-3 py-1"><?= $row->order_count ?> đơn</span>
                                </td>
                                <td class="text-end fw-bold text-danger"><?= number_format($row->daily_revenue) ?>đ</td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-chart-bar fa-3x mb-3 d-block"></i> Không có dữ liệu doanh thu trong
                                khoảng thời gian đã chọn.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js CDN để vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chuẩn bị dữ liệu từ PHP cho Javascript Chart
    const chartLabels = <?= json_encode(array_map(fn($r) => date('d/m', strtotime($r->date)), $reportData ?? [])) ?>;
    const chartData = <?= json_encode(array_map(fn($r) => $r->daily_revenue, $reportData ?? [])) ?>;

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: chartData,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointBackgroundColor: '#0d6efd'
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
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            }
        }
    });
</script>