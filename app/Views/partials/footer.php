<!-- FOOTER CLIENT -->
<footer class="footer bg-dark text-light pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Cột 1: Thông tin thương hiệu -->
            <div class="col-lg-4 col-md-6">
                <a href="/" class="d-flex align-items-center text-white text-decoration-none fs-4 fw-bold mb-3">
                    <i class="fa-solid fa-house-laptop text-primary me-2"></i> HomeApp Shop
                </a>
                <p class="text-secondary small">
                    Chuyên cung cấp các thiết bị gia dụng, nhà bếp thông minh chính hãng. Mang đến tiện nghi và trải
                    nghiệm sống hiện đại cho gia đình Việt.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                            class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                            class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                            class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Cột 2: Danh mục nhanh -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Danh Mục Hot</h6>
                <ul class="list-unstyled text-secondary small d-flex flex-column gap-2 mb-0">
                    <li><a href="/products?category=noi-chien" class="text-secondary text-decoration-none">Nồi chiên
                            không dầu</a></li>
                    <li><a href="/products?category=may-hut-bui" class="text-secondary text-decoration-none">Máy hút bụi
                            robot</a></li>
                    <li><a href="/products?category=lo-vi-song" class="text-secondary text-decoration-none">Lò vi sóng &
                            Lò nướng</a></li>
                    <li><a href="/products?category=may-xay" class="text-secondary text-decoration-none">Máy xay sinh
                            tố</a></li>
                </ul>
            </div>

            <!-- Cột 3: Chính sách & Hỗ trợ -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Chính Sách & Hỗ Trợ</h6>
                <ul class="list-unstyled text-secondary small d-flex flex-column gap-2 mb-0">
                    <li><a href="#" class="text-secondary text-decoration-none">Chính sách bảo hành 12-24 tháng</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Chính sách đổi trả trong 30 ngày</a>
                    </li>
                    <li><a href="#" class="text-secondary text-decoration-none">Chính sách vận chuyển & Giao hàng</a>
                    </li>
                    <li><a href="#" class="text-secondary text-decoration-none">Hướng dẫn thanh toán online</a></li>
                </ul>
            </div>

            <!-- Cột 4: Liên hệ -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Thông Tin Liên Hệ</h6>
                <ul class="list-unstyled text-secondary small d-flex flex-column gap-2 mb-3">
                    <li><i class="fa-solid fa-location-dot me-2 text-primary"></i>123 Đường Nguyễn Huệ, Quận 1, TP.HCM
                    </li>
                    <li><i class="fa-solid fa-phone me-2 text-primary"></i>Hotline: 1900 xxxx (8:00 - 21:00)</li>
                    <li><i class="fa-solid fa-envelope me-2 text-primary"></i>Email: support@homeapp.vn</li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center text-secondary small">
            <p class="mb-2 mb-sm-0">&copy; 2026 HomeApp Shop. Tất cả quyền được bảo lưu.</p>
            <div class="d-flex gap-3">
                <span><i class="fa-solid fa-shield-halved me-1"></i>Bảo mật thông tin</span>
                <span><i class="fa-solid fa-truck-fast me-1"></i>Giao hàng toàn quốc</span>
            </div>
        </div>
    </div>
</footer>

<script>
// Phòng chống việc nhúng Script bị trùng lặp nhiều lần trên cùng 1 trang
if (!window.cartAddListenerAttached) {
    window.cartAddListenerAttached = true;

    document.addEventListener('submit', function (e) {
        // Bắt tất cả form có action chứa /cart/add
        if (e.target && e.target.action && e.target.action.includes('/cart/add')) {
            e.preventDefault(); // CHẶN CHUYỂN TRANG BẮT BUỘC

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Khóa nút tạm thời để tránh bấm liên tục
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(form);
            formData.append('ajax', '1');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 1. Cập nhật Badge giỏ hàng trên Header ngay lập tức
                    const badge = document.querySelector('.navbar .badge');
                    if (badge) {
                        badge.innerText = data.cartCount;
                    }
                    
                    // 2. Thông báo cho người dùng
                    alert(data.message || 'Thêm vào giỏ hàng thành công!');
                } else {
                    alert(data.message || 'Không thể thêm vào giỏ hàng!');
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
            });
        }
    });
}
</script>