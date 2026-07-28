<!-- SIDEBAR ADMIN -->
<aside id="sidebar" class="bg-dark text-white min-vh-100 d-flex flex-column" style="width: 260px; min-width: 260px;">
    <!-- Brand / Logo -->
    <div class="sidebar-brand p-3 border-bottom border-secondary d-flex align-items-center gap-2">
        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center"
            style="width: 38px; height: 38px;">
            <i class="fa-solid fa-user-shield fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-white">HomeApp Admin</h6>
            <small class="text-success"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i>Hệ thống sẵn
                sàng</small>
        </div>
    </div>

    <!-- Danh sách Menu Điều Hướng -->
    <div class="p-3 flex-grow-1">
        <div class="text-uppercase small fw-bold text-secondary mb-2 px-2">Quản lý chung</div>
        <ul class="nav nav-pills flex-column gap-1 mb-4">
            <li class="nav-item">
                <a href="/admin/dashboard"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= ($_SERVER['REQUEST_URI'] == '/admin/dashboard') ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-chart-line" style="width: 20px;"></i>
                    <span>Bảng điều khiển</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/categories"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/categories')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-list-check" style="width: 20px;"></i>
                    <span>Quản lý Danh mục</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/products"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/products')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i>
                    <span>Quản lý Sản phẩm</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/orders"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/orders')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-receipt" style="width: 20px;"></i>
                    <span>Quản lý Đơn hàng</span>
                </a>
            </li>
        </ul>

        <div class="text-uppercase small fw-bold text-secondary mb-2 px-2">Cài đặt & Tài khoản</div>
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="/admin/profile"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/profile')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-id-card" style="width: 20px;"></i>
                    <span>Hồ sơ cá nhân</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/users"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/users')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-users" style="width: 20px;"></i>
                    <span>Quản lý Người dùng</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/settings"
                    class="nav-link text-white-50 d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?= (str_contains($_SERVER['REQUEST_URI'], '/admin/settings')) ? 'active bg-primary text-white' : '' ?>">
                    <i class="fa-solid fa-gear" style="width: 20px;"></i>
                    <span>Cấu hình hệ thống</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Đăng xuất -->
    <div class="p-3 border-top border-secondary">
        <a href="/logout"
            class="btn btn-outline-danger btn-sm w-100 rounded-3 d-flex align-items-center justify-content-center gap-2 py-2">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Đăng xuất Admin</span>
        </a>
    </div>
</aside>