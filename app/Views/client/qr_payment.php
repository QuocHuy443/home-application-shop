<?php
/**
 * Giao diện Thanh Toán Qua Mã QR Ngân Hàng (VietQR)
 * @var object $order
 * @var string $qrUrl
 * @var array $bankConfig
 */
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/cart" class="text-decoration-none">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán QR</li>
        </ol>
    </nav>

    <!-- Header Thông báo -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="fa-solid fa-clock me-1"></i> Đang chờ thanh toán
                </span>
                <h4 class="fw-bold mb-1">Thanh Toán Chuyển Khoản Qua Mã QR</h4>
                <p class="text-muted small mb-0">Mã đơn hàng: <strong class="text-primary"><?= htmlspecialchars($order->order_code) ?></strong></p>
            </div>
            <div class="text-end">
                <small class="text-muted d-block">Thời gian giữ đơn hàng còn lại:</small>
                <span class="fw-bold text-danger fs-4" id="countdownTimer">15:00</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- CỘT TRÁI: MÃ QR CODE & KHU VỰC GIẢ LẬP DEMO -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-qrcode text-primary me-2"></i>Mã VietQR Động</h5>
                    <p class="text-muted small mb-3">Mở ứng dụng Ngân hàng (MBBank, Vietcombank, Momo...) để quét mã bên dưới</p>

                    <!-- Khung ảnh QR hiệu ứng -->
                    <div class="qr-container position-relative d-inline-block p-3 rounded-4 bg-light border shadow-sm mb-3">
                        <img src="<?= htmlspecialchars($qrUrl) ?>" alt="VietQR Code" class="img-fluid rounded-3" style="max-width: 260px; height: auto;">
                        <div class="qr-scan-line"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                        <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill small">
                            <i class="fa-solid fa-shield-halved me-1"></i> Tự động điền số tiền & Nội dung
                        </span>
                    </div>
                </div>

                <!-- KHU VỰC GIẢ LẬP BÁO CÁO ĐỒ ÁN (MOCK DEMO) -->
                <div class="demo-box p-3 rounded-4 bg-primary-subtle border border-primary text-start mt-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary text-white">🧪 KHU VỰC THỬ NGHIỆM DEMO</span>
                    </div>
                    <p class="small text-dark mb-3">
                        Đây là môi trường thử nghiệm cho Đồ án môn học. Bấm nút bên dưới để <strong>giả lập phản hồi của Ngân hàng</strong> xác nhận đã nhận tiền.
                    </p>
                    
                    <button type="button" id="btnSimulatePayment" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2">
                        <i class="fa-solid fa-bolt me-2"></i> Giả Lập Thầy Quét QR & Chuyển Tiền Thành Công
                    </button>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: THÔNG TIN TÀI KHOẢN & TÓM TẮT ĐƠN HÀNG -->
        <div class="col-lg-7">
            <!-- Thông tin chuyển khoản chi tiết -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-building-columns text-primary me-2"></i>Thông Tin Chuyển Khoản Thủ Công</h5>
                
                <div class="list-group list-group-flush rounded-3 border">
                    <!-- Ngân hàng -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Ngân hàng thụ hưởng:</span>
                        <span class="fw-bold text-dark"><?= htmlspecialchars($bankConfig['bank_name']) ?></span>
                    </div>

                    <!-- Chủ tài khoản -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Chủ tài khoản:</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark"><?= htmlspecialchars($bankConfig['account_name']) ?></span>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" onclick="copyToClipboard('<?= htmlspecialchars($bankConfig['account_name']) ?>', this)">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Số tài khoản -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Số tài khoản:</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold fs-5 text-primary"><?= htmlspecialchars($bankConfig['account_no']) ?></span>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1" onclick="copyToClipboard('<?= htmlspecialchars($bankConfig['account_no']) ?>', this)">
                                <i class="fa-solid fa-copy me-1"></i> Coppy STK
                            </button>
                        </div>
                    </div>

                    <!-- Số tiền -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Số tiền cần chuyển:</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold fs-4 text-danger"><?= number_format($order->total_amount) ?> VNĐ</span>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" onclick="copyToClipboard('<?= (int)$order->total_amount ?>', this)">
                                <i class="fa-solid fa-copy me-1"></i> Sao chép số tiền
                            </button>
                        </div>
                    </div>

                    <!-- Nội dung chuyển khoản -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                        <div>
                            <span class="text-muted small d-block">Nội dung chuyển khoản (Bắt buộc):</span>
                            <small class="text-danger">Vui lòng giữ nguyên chính xác nội dung này</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-3 fw-bold"><?= htmlspecialchars($order->order_code) ?></span>
                            <button class="btn btn-sm btn-warning rounded-pill px-2 py-1 fw-semibold" onclick="copyToClipboard('<?= htmlspecialchars($order->order_code) ?>', this)">
                                <i class="fa-solid fa-copy me-1"></i> Copy Nội dung
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt sản phẩm trong đơn -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-3">Sản phẩm trong đơn hàng (<?= count($order->items) ?>)</h6>
                <div class="overflow-auto pe-1" style="max-height: 200px;">
                    <?php foreach ($order->items as $item): ?>
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <img src="/<?= htmlspecialchars($item->product->thumbnail ?? '') ?>" class="rounded-3 border" style="width: 42px; height: 42px; object-fit: contain;">
                            <div>
                                <h6 class="mb-0 small fw-semibold text-truncate" style="max-width: 250px;"><?= htmlspecialchars($item->product->name ?? 'Sản phẩm') ?></h6>
                                <small class="text-muted"><?= number_format($item->price) ?>đ x <?= $item->quantity ?></small>
                            </div>
                        </div>
                        <span class="fw-bold small"><?= number_format($item->price * $item->quantity) ?>đ</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token Meta/Input for AJAX -->
<form id="confirmForm" style="display: none;">
    <?= \App\Helpers\CsrfHelper::csrfField() ?>
</form>

<style>
/* Hiệu ứng tia quét QR Code */
.qr-container {
    overflow: hidden;
}
.qr-scan-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #0d6efd, transparent);
    box-shadow: 0 0 10px #0d6efd;
    animation: scanAnimation 2.5s infinite linear;
}
@keyframes scanAnimation {
    0% { top: 5%; opacity: 0; }
    50% { opacity: 1; }
    100% { top: 95%; opacity: 0; }
}

/* Toast thông báo sao chép */
.copy-toast {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 9999;
    background: #198754;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: fadeInUp 0.3s ease;
}
</style>

<script>
// Hàm sao chép văn bản (Hỗ trợ cả HTTP & HTTPS)
function copyToClipboard(text, btnElement) {
    const strText = String(text);

    function showToast() {
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fa-solid fa-check text-success"></i> Đã chép!';
        setTimeout(() => {
            btnElement.innerHTML = originalText;
        }, 2000);

        // Hiển thị Toast thông báo
        const existingToast = document.querySelector('.copy-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = 'copy-toast small fw-bold';
        toast.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>Đã sao chép: "' + strText + '"';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(strText)
            .then(showToast)
            .catch(() => fallbackCopy(strText, showToast));
    } else {
        fallbackCopy(strText, showToast);
    }
}

// Fallback copy cho môi trường HTTP (non-secure context)
function fallbackCopy(text, callback) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            callback();
        } else {
            prompt("Hãy nhấn Ctrl+C (hoặc Cmd+C) để sao chép:", text);
        }
    } catch (err) {
        prompt("Hãy nhấn Ctrl+C (hoặc Cmd+C) để sao chép:", text);
    }
    document.body.removeChild(textArea);
}

// Bộ đếm ngược 15 phút
let duration = 15 * 60;
const timerDisplay = document.getElementById('countdownTimer');
const timerInterval = setInterval(function () {
    let minutes = parseInt(duration / 60, 10);
    let seconds = parseInt(duration % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    timerDisplay.textContent = minutes + ":" + seconds;

    if (--duration < 0) {
        clearInterval(timerInterval);
        timerDisplay.textContent = "Hết giờ!";
    }
}, 1000);

// Xử lý sự kiện bấm nút Giả lập Thanh toán Demo
document.getElementById('btnSimulatePayment').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang kết nối Ngân hàng xử lý...';

    // Giả lập delay 1.5s tạo hiệu ứng sống động
    setTimeout(() => {
        const csrfToken = document.querySelector('#confirmForm input[name="csrf_token"]').value;

        fetch('/checkout/confirm-qr/<?= $order->id ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'csrf_token=' + encodeURIComponent(csrfToken)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> Thanh Toán Thành Công! Đang chuyển hướng...';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
            } else {
                alert(data.message || 'Xảy ra lỗi xác nhận thanh toán');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i> Thử lại Giả lập Thanh Toán';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Có lỗi mạng xảy ra!');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i> Thử lại Giả lập Thanh Toán';
        });
    }, 1500);
});
</script>
