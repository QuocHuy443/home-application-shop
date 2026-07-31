================================================================================
           HƯỚNG DẪN TẢI, CÀI ĐẶT VÀ VẬN HÀNH HỆ THỐNG HOMEAPP SHOP
                  (Dự án Website Bán Đồ Gia Dụng PHP MVC)
================================================================================


I. YÊU CẦU HỆ THỐNG
--------------------------------------------------------------------------------
1. Phần mềm môi trường Web Server:
   - Laragon (Khuyên dùng) hoặc XAMPP / WAMP.
   - PHP phiên bản: 8.x trở lên.
   - Cơ sở dữ liệu: MySQL (MariaDB).
2. Công cụ hỗ trợ:
   - Composer (Trình quản lý gói thư viện PHP).
   - Trình duyệt web hiện đại (Google Chrome, Microsoft Edge, Brave...).



II. HƯỚNG DẪN CÀI ĐẶT DỰ ÁN
--------------------------------------------------------------------------------
Bước 1: Giải nén mã nguồn
   - Tải file mã nguồn dự án về máy tính.
   - Giải nén thư mục dự án (ví dụ: home-application-shop) vào thư mục web:
     + Nếu dùng Laragon: C:\laragon\www\home-application-shop
     + Nếu dùng XAMPP:   C:\xampp\htdocs\home-application-shop

Bước 2: Cài đặt các thư viện phụ thuộc (Dependencies)
   - Mở Terminal / Command Prompt (CMD) tại thư mục gốc của dự án.
   - Chạy lệnh sau để tự động nạp các thư viện bắt buộc (phân trang, gửi mail, ORM):

     composer install
     composer require phpmailer/phpmailer
     composer require illuminate/pagination

Bước 3: Khởi tạo Cơ sở dữ liệu
   - Mở phần mềm Laragon/XAMPP, bấm nút "Start All" để khởi động Apache và MySQL.
   - Mở PHPMyAdmin hoặc Navicat/HeidiSQL, tạo 1 CSDL mới với thông số:
     + Tên CSDL: home_appliance_shop
     + Bảng mã (Collation): utf8mb4_unicode_ci

     (Hoặc chạy câu lệnh SQL: 
      CREATE DATABASE home_appliance_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; )

Bước 4: Cấu hình file môi trường (.env)
   - Tạo file `.env` tại thư mục gốc dự án (nếu chưa có) và thiết lập thông số DB:
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=home_appliance_shop
     DB_USERNAME=root
     DB_PASSWORD=

Bước 5: Khởi tạo Bảng & Nạp Dữ Liệu Mẫu (Migration & Seeder)
   - Tại cửa sổ Terminal/CMD của thư mục dự án, chạy lần lượt 2 lệnh sau:

     php database/migrate.php
     php database/seeder.php

   - Khi hệ thống báo "Đã nạp dữ liệu thành công!", toàn bộ 11 bảng cùng các sản phẩm
     đồ gia dụng, danh mục và tài khoản mẫu đã được khởi tạo xong.



III. HƯỚNG DẪN TRẢI NGHIỆM VÀ SỬ DỤNG
--------------------------------------------------------------------------------
1. Đường dẫn truy cập Website:
   - Nếu dùng Laragon:  http://home-application-shop.test/
   - Nếu dùng PHP CLI:  Chạy lệnh `php -S localhost:8080 -t public` 
                        sau đó vào  http://localhost:8080/

2. Danh sách tài khoản mẫu sau khi Seed:
   a) Tài khoản Quản trị viên (Admin):
      - Email:    admin@gmail.com
      - Mật khẩu: 123456
      - Vai trò:  Quản lý Dashboard KPI, Sản phẩm, 
                  Danh mục, Duyệt Đơn hàng, Mở/Khóa User và Bật/Tắt Chế độ bảo trì.

   b) Tài khoản Khách hàng (Client / Customer):
      - Email:    thienlam@gmail.com
      - Mật khẩu: 123456
      - Vai trò:  Tra cứu đồ gia dụng, Lọc sản phẩm, Thêm giỏ hàng ngầm (AJAX), 
                  Đặt hàng, Quét mã VietQR tự động fill thông tin, Xem đơn hàng cá nhân.



IV. HỖ TRỢ KỸ THUẬT & NHÓM THỰC HIỆN
--------------------------------------------------------------------------------
- Dự án: Đồ án Lập trình Mã nguồn mở - Website Bán Đồ Gia Dụng HomeApp Shop
- Trường: Đại học Công Thương TP.HCM (HUIT) - Khoa Công nghệ Thông tin
- Giảng viên hướng dẫn: Nguyễn Thanh Truyền
- Nhóm sinh viên thực hiện: Nhóm 4
  1. Phạm Trần Văn Quốc Huy   (MSSV: 2001230308)
  2. Lê Ngọc Anh          (MSSV: 2001230017)
  3. Trần Anh Tuấn        (MSSV: 2001230849)
  4. Trần Xuân Vỷ         (MSSV: 2001231069)
  5. Nguyễn Quang Bảo Tâm (MSSV: 2001230780)

* Lưu ý: Mọi nhật ký lỗi ngầm của hệ thống sẽ được ghi tự động tại thư mục: `storage/logs/`
================================================================================