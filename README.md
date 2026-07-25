# Home Appliance Shop - Hướng dẫn vận hành

## Yêu cầu hệ thống
- PHP 8.x
- MySQL
- Composer

## Cài đặt
1. Cài đặt các thư viện:
   ```bash
   composer install
   ```
2. Copy `.env.example` thành `.env` và cấu hình kết nối database `DB_DATABASE=home_appliance_shop`.
3. Tạo cơ sở dữ liệu `home_appliance_shop` trong MySQL (nếu chưa có):
   ```sql
   CREATE DATABASE home_appliance_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
4. Khởi tạo các bảng và dữ liệu mẫu:
   ```bash
   php database/migrate.php
   php database/seeder.php
   ```
5. Đăng nhập Admin với `admin@gmail.com` / `123456`.
6. Chạy các file kiểm thử manual:
   ```bash
   php test_auth.php
   php test_cart_checkout.php
   ```