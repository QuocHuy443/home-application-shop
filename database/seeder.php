<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Database\Capsule\Manager as Capsule;

echo "--- BẮT ĐẦU GIEO DỮ LIỆU MẪU ---\n";

Capsule::statement('SET FOREIGN_KEY_CHECKS = 0;');

// Clear old data
Role::truncate();
User::truncate();
Category::truncate();
Product::truncate();
ProductImage::truncate();
Cart::truncate();
CartItem::truncate();
Order::truncate();
OrderItem::truncate();
Payment::truncate();

Capsule::statement('SET FOREIGN_KEY_CHECKS = 1;');

// 1. Roles
$adminRole = Role::create(['name' => 'admin', 'description' => 'Quản trị viên hệ thống']);
$customerRole = Role::create(['name' => 'customer', 'description' => 'Khách hàng mua sắm']);

// 2. Users
$adminUser = User::create([
    'email' => 'admin@gmail.com',
    'name' => 'Administrator',
    'password' => password_hash('123456', PASSWORD_BCRYPT),
    'phone' => '0901234567',
    'address' => 'TP. Hồ Chí Minh',
    'role_id' => $adminRole->id
]);

$customerUser = User::create([
    'email' => 'thienlam@gmail.com',
    'name' => 'Thiên Lâm',
    'password' => password_hash('123456', PASSWORD_BCRYPT),
    'phone' => '0987654321',
    'address' => 'Thủ Đức, TP. Hồ Chí Minh',
    'role_id' => $customerRole->id
]);

// 3. Categories
$catKitchen = Category::create(['slug' => 'thiet-bi-nha-bep', 'name' => 'Thiết Bị Nhà Bếp', 'is_active' => true]);
$catClean = Category::create(['slug' => 'dien-gia-dung', 'name' => 'Điện Gia Dụng', 'is_active' => true]);
$catSmart = Category::create(['slug' => 'nha-thong-minh', 'name' => 'Nhà Thông Minh', 'is_active' => true]);

// 4. Products
$p1 = Product::create([
    'slug' => 'noi-com-dien-cuckoo-18l',
    'name' => 'Nồi Cơm Điện Cuckoo 1.8L',
    'description' => 'Công nghệ nấu cao tần IH giúp cơm chín đều, giữ trọn dưỡng chất.',
    'price' => 2450000,
    'stock' => 20,
    'thumbnail' => 'uploads/products/cuckoo-18l.jpg',
    'category_id' => $catKitchen->id
]);

$p2 = Product::create([
    'slug' => 'robot-hut-bui-ecovacs-deebot',
    'name' => 'Robot Hút Bụi Ecovacs Deebot',
    'description' => 'Lực hút 5000Pa, định vị Laser thông minh, điều khiển qua app.',
    'price' => 8900000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/ecovacs-deebot.jpg',
    'category_id' => $catClean->id
]);

$p3 = Product::create([
    'slug' => 'noi-chien-khong-dau-philips',
    'name' => 'Nồi Chiên Không Dầu Philips 6.2L',
    'description' => 'Công nghệ Rapid Air giảm 90% chất béo, màn hình cảm ứng.',
    'price' => 3200000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/philips-airfryer.jpg',
    'category_id' => $catKitchen->id
]);

$p4 = Product::create([
    'slug' => 'lo-vi-song-samsung',
    'name' => 'Lò Vi Sóng Samsung 23L',
    'description' => 'Tráng men Ceramic dễ lau chùi, rã đông nhanh.',
    'price' => 1850000,
    'stock' => 25,
    'thumbnail' => 'uploads/products/samsung-microwave.jpg',
    'category_id' => $catKitchen->id
]);

$p5 = Product::create([
    'slug' => 'may-xay-sinh-to-panasonic',
    'name' => 'Máy Xay Sinh Tố Panasonic',
    'description' => 'Công suất 600W, cối thủy tinh chịu lực, xay đá nhuyễn mịn.',
    'price' => 1250000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/panasonic-blender.jpg',
    'category_id' => $catKitchen->id
]);

$p6 = Product::create([
    'slug' => 'khoa-cua-dien-tu-xiaomi',
    'name' => 'Khóa Cửa Điện Tử Xiaomi',
    'description' => 'Mở khóa bằng vân tay, thẻ từ, mã số, kết nối Mi Home.',
    'price' => 4500000,
    'stock' => 8,
    'thumbnail' => 'uploads/products/xiaomi-lock.jpg',
    'category_id' => $catSmart->id
]);

// 5. Product Images
ProductImage::create(['image_path' => 'uploads/products/cuckoo-detail-1.jpg', 'product_id' => $p1->id]);
ProductImage::create(['image_path' => 'uploads/products/ecovacs-detail-1.jpg', 'product_id' => $p2->id]);
ProductImage::create(['image_path' => 'uploads/products/philips-detail-1.jpg', 'product_id' => $p3->id]);
ProductImage::create(['image_path' => 'uploads/products/samsung-detail-1.jpg', 'product_id' => $p4->id]);
ProductImage::create(['image_path' => 'uploads/products/panasonic-detail-1.jpg', 'product_id' => $p5->id]);
ProductImage::create(['image_path' => 'uploads/products/xiaomi-detail-1.jpg', 'product_id' => $p6->id]);

// 6 & 7. Cart & Cart Items
$cart = Cart::create(['user_id' => $customerUser->id]);
CartItem::create(['cart_id' => $cart->id, 'product_id' => $p1->id, 'quantity' => 1]);

// 8 & 9. Order & Order Items
$order = Order::create([
    'order_code' => 'GD-' . time(),
    'user_id' => $customerUser->id,
    'total_amount' => 2450000,
    'shipping_name' => 'Thiên Lâm',
    'shipping_phone' => '0987654321',
    'shipping_address' => 'Thủ Đức, TP. Hồ Chí Minh',
    'status' => 'pending',
    'note' => 'Giao hàng giờ hành chính'
]);

OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $p1->id,
    'quantity' => 1,
    'price' => 2450000
]);

// 10. Payments
Payment::create([
    'order_id' => $order->id,
    'payment_method' => 'COD',
    'payment_status' => 'unpaid',
    'transaction_id' => null
]);

echo "[✓] Đã nạp dữ liệu thành công cho đúng 11 bảng!\n";
