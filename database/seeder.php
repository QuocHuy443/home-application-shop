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

// // 4. Products
// $p1 = Product::create([
//     'slug' => 'noi-com-dien-cuckoo-18l',
//     'name' => 'Nồi Cơm Điện Cuckoo 1.8L',
//     'description' => 'Công nghệ nấu cao tần IH giúp cơm chín đều, giữ trọn dưỡng chất.',
//     'price' => 2450000,
//     'stock' => 20,
//     'thumbnail' => 'uploads/products/cuckoo-18l.jpg',
//     'category_id' => $catKitchen->id
// ]);

// $p2 = Product::create([
//     'slug' => 'robot-hut-bui-ecovacs-deebot',
//     'name' => 'Robot Hút Bụi Ecovacs Deebot',
//     'description' => 'Lực hút 5000Pa, định vị Laser thông minh, điều khiển qua app.',
//     'price' => 8900000,
//     'stock' => 10,
//     'thumbnail' => 'uploads/products/ecovacs-deebot.jpg',
//     'category_id' => $catClean->id
// ]);

// $p3 = Product::create([
//     'slug' => 'noi-chien-khong-dau-philips',
//     'name' => 'Nồi Chiên Không Dầu Philips 6.2L',
//     'description' => 'Công nghệ Rapid Air giảm 90% chất béo, màn hình cảm ứng.',
//     'price' => 3200000,
//     'stock' => 15,
//     'thumbnail' => 'uploads/products/philips-airfryer.jpg',
//     'category_id' => $catKitchen->id
// ]);

// $p4 = Product::create([
//     'slug' => 'lo-vi-song-samsung',
//     'name' => 'Lò Vi Sóng Samsung 23L',
//     'description' => 'Tráng men Ceramic dễ lau chùi, rã đông nhanh.',
//     'price' => 1850000,
//     'stock' => 25,
//     'thumbnail' => 'uploads/products/samsung-microwave.jpg',
//     'category_id' => $catKitchen->id
// ]);

// $p5 = Product::create([
//     'slug' => 'may-xay-sinh-to-panasonic',
//     'name' => 'Máy Xay Sinh Tố Panasonic',
//     'description' => 'Công suất 600W, cối thủy tinh chịu lực, xay đá nhuyễn mịn.',
//     'price' => 1250000,
//     'stock' => 30,
//     'thumbnail' => 'uploads/products/panasonic-blender.jpg',
//     'category_id' => $catKitchen->id
// ]);

// $p6 = Product::create([
//     'slug' => 'khoa-cua-dien-tu-xiaomi',
//     'name' => 'Khóa Cửa Điện Tử Xiaomi',
//     'description' => 'Mở khóa bằng vân tay, thẻ từ, mã số, kết nối Mi Home.',
//     'price' => 4500000,
//     'stock' => 8,
//     'thumbnail' => 'uploads/products/xiaomi-lock.jpg',
//     'category_id' => $catSmart->id
// ]);

// // 5. Product Images
// ProductImage::create(['image_path' => 'uploads/products/cuckoo-detail-1.jpg', 'product_id' => $p1->id]);
// ProductImage::create(['image_path' => 'uploads/products/ecovacs-detail-1.jpg', 'product_id' => $p2->id]);
// ProductImage::create(['image_path' => 'uploads/products/philips-detail-1.jpg', 'product_id' => $p3->id]);
// ProductImage::create(['image_path' => 'uploads/products/samsung-detail-1.jpg', 'product_id' => $p4->id]);
// ProductImage::create(['image_path' => 'uploads/products/panasonic-detail-1.jpg', 'product_id' => $p5->id]);
// ProductImage::create(['image_path' => 'uploads/products/xiaomi-detail-1.jpg', 'product_id' => $p6->id]);

// =========================
// THIẾT BỊ NHÀ BẾP
// =========================

$p1 = Product::create([
    'slug' => 'noi-com-dien-cuckoo-cr-1021',
    'name' => 'Nồi Cơm Điện Cuckoo CR-1021 1.8L',
    'description' => 'Nồi cơm điện dung tích 1.8L, công nghệ gia nhiệt 3D giúp cơm chín đều và giữ ấm lâu.',
    'price' => 2490000,
    'stock' => 20,
    'thumbnail' => 'https://cdn11.dienmaycholon.vn/filewebdmclnew/DMCL21/Picture//Apro/Apro_product_37500/noi-com-dien-kangaroo-kgrc18m3-18-lit-main--508.png',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p1->id,
    'image_path' => 'https://cdn11.dienmaycholon.vn/filewebdmclnew/DMCL21/Picture//Apro/Apro_product_37500/noi-com-dien-kangaroo-kgrc18m3-18-lit-main--508.png'
]);

$p2 = Product::create([
    'slug' => 'noi-com-dien-sharp-ks-n18',
    'name' => 'Nồi Cơm Điện Sharp KS-N18',
    'description' => 'Dung tích 1.8L, lòng nồi chống dính cao cấp, tiết kiệm điện.',
    'price' => 1290000,
    'stock' => 25,
    'thumbnail' => 'https://unie.com.vn/wp-content/uploads/2022/04/1_UNIE-UE-625-510x510.png',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p2->id,
    'image_path' => 'https://unie.com.vn/wp-content/uploads/2022/04/1_UNIE-UE-625-510x510.png'
]);

$p3 = Product::create([
    'slug' => 'noi-com-dien-panasonic-sr-mvn',
    'name' => 'Nồi Cơm Điện Panasonic SR-MVN',
    'description' => 'Công nghệ nấu Fuzzy Logic, lòng nồi hợp kim nhôm phủ chống dính.',
    'price' => 1890000,
    'stock' => 18,
    'thumbnail' => 'uploads/products/panasonic-srmvn.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p3->id,
    'image_path' => 'uploads/products/panasonic-srmvn.jpg'
]);

$p4 = Product::create([
    'slug' => 'noi-chien-khong-dau-philips-hd9270',
    'name' => 'Nồi Chiên Không Dầu Philips HD9270',
    'description' => 'Dung tích 6.2L, công nghệ Rapid Air giúp giảm đến 90% lượng dầu mỡ.',
    'price' => 4290000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/philips-hd9270.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p4->id,
    'image_path' => 'uploads/products/philips-hd9270.jpg'
]);

$p5 = Product::create([
    'slug' => 'noi-chien-locklock-ejf296',
    'name' => 'Nồi Chiên Không Dầu Lock&Lock EJF296',
    'description' => 'Dung tích 5.5L, bảng điều khiển cảm ứng hiện đại.',
    'price' => 2890000,
    'stock' => 20,
    'thumbnail' => 'uploads/products/locklock-ejf296.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p5->id,
    'image_path' => 'uploads/products/locklock-ejf296.jpg'
]);

$p6 = Product::create([
    'slug' => 'lo-vi-song-samsung-ms23',
    'name' => 'Lò Vi Sóng Samsung MS23',
    'description' => 'Dung tích 23L, khoang lò phủ Ceramic Enamel chống bám bẩn.',
    'price' => 2390000,
    'stock' => 16,
    'thumbnail' => 'uploads/products/samsung-ms23.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p6->id,
    'image_path' => 'uploads/products/samsung-ms23.jpg'
]);

$p7 = Product::create([
    'slug' => 'lo-vi-song-sharp-rg222',
    'name' => 'Lò Vi Sóng Sharp R-G222VN',
    'description' => 'Lò vi sóng có chức năng nướng, công suất 800W.',
    'price' => 2690000,
    'stock' => 14,
    'thumbnail' => 'uploads/products/sharp-rg222.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p7->id,
    'image_path' => 'uploads/products/sharp-rg222.jpg'
]);

$p8 = Product::create([
    'slug' => 'may-xay-sinh-to-panasonic-mxv310',
    'name' => 'Máy Xay Sinh Tố Panasonic MX-V310',
    'description' => 'Công suất 600W, cối thủy tinh chịu lực, xay đá tốt.',
    'price' => 1490000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/panasonic-mxv310.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p8->id,
    'image_path' => 'uploads/products/panasonic-mxv310.jpg'
]);

$p9 = Product::create([
    'slug' => 'may-ep-cham-kangaroo-kg150sj',
    'name' => 'Máy Ép Chậm Kangaroo KG150SJ',
    'description' => 'Ép chậm giúp giữ nguyên vitamin và dưỡng chất trong trái cây.',
    'price' => 1990000,
    'stock' => 18,
    'thumbnail' => 'uploads/products/kangaroo-kg150sj.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p9->id,
    'image_path' => 'uploads/products/kangaroo-kg150sj.jpg'
]);

$p10 = Product::create([
    'slug' => 'may-pha-ca-phe-delonghi-ec685',
    'name' => 'Máy Pha Cà Phê Delonghi EC685',
    'description' => 'Máy pha Espresso bán tự động, áp suất bơm 15 bar.',
    'price' => 6990000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/delonghi-ec685.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p10->id,
    'image_path' => 'uploads/products/delonghi-ec685.jpg'
]);
// ===================================================
// SẢN PHẨM 11 - 20
// ===================================================

$p11 = Product::create([
    'slug' => 'am-sieu-toc-electrolux-eek1303w',
    'name' => 'Ấm Siêu Tốc Electrolux EEK1303W 1.7L',
    'description' => 'Dung tích 1.7L, công suất 2200W, tự ngắt khi nước sôi.',
    'price' => 690000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/electrolux-eek1303w.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p11->id,
    'image_path' => 'uploads/products/electrolux-eek1303w.jpg'
]);

$p12 = Product::create([
    'slug' => 'bep-tu-sunhouse-shd6152',
    'name' => 'Bếp Từ Sunhouse SHD6152',
    'description' => 'Bếp từ đơn công suất 2000W, mặt kính chịu nhiệt cao cấp.',
    'price' => 1590000,
    'stock' => 18,
    'thumbnail' => 'uploads/products/sunhouse-shd6152.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p12->id,
    'image_path' => 'uploads/products/sunhouse-shd6152.jpg'
]);

$p13 = Product::create([
    'slug' => 'may-hut-mui-hafele-hcbi70b',
    'name' => 'Máy Hút Mùi Hafele HC-BI70B',
    'description' => 'Thiết kế âm tủ, công suất hút mạnh, vận hành êm.',
    'price' => 5490000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/hafele-hcbi70b.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p13->id,
    'image_path' => 'uploads/products/hafele-hcbi70b.jpg'
]);

$p14 = Product::create([
    'slug' => 'noi-ap-suat-philips-hd2137',
    'name' => 'Nồi Áp Suất Điện Philips HD2137',
    'description' => 'Dung tích 6L, nhiều chế độ nấu tự động.',
    'price' => 2790000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/philips-hd2137.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p14->id,
    'image_path' => 'uploads/products/philips-hd2137.jpg'
]);

$p15 = Product::create([
    'slug' => 'may-nuong-banh-mi-tefal-tt3408',
    'name' => 'Máy Nướng Bánh Mì Tefal TT3408',
    'description' => '7 mức điều chỉnh nhiệt, chức năng rã đông và hâm nóng.',
    'price' => 990000,
    'stock' => 22,
    'thumbnail' => 'uploads/products/tefal-tt3408.jpg',
    'category_id' => $catKitchen->id
]);

ProductImage::create([
    'product_id' => $p15->id,
    'image_path' => 'uploads/products/tefal-tt3408.jpg'
]);

// ===========================
// ĐIỆN GIA DỤNG
// ===========================

$p16 = Product::create([
    'slug' => 'robot-hut-bui-ecovacs-deebot-t20',
    'name' => 'Robot Hút Bụi Ecovacs Deebot T20',
    'description' => 'Lực hút 6000Pa, lau nhà nước nóng, điều khiển qua ứng dụng.',
    'price' => 14990000,
    'stock' => 8,
    'thumbnail' => 'uploads/products/ecovacs-t20.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p16->id,
    'image_path' => 'uploads/products/ecovacs-t20.jpg'
]);

$p17 = Product::create([
    'slug' => 'robot-hut-bui-xiaomi-x10-plus',
    'name' => 'Robot Hút Bụi Xiaomi Robot Vacuum X10+',
    'description' => 'Tự giặt giẻ lau, tự đổ rác, điều khiển bằng Mi Home.',
    'price' => 13990000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/xiaomi-x10plus.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p17->id,
    'image_path' => 'uploads/products/xiaomi-x10plus.jpg'
]);

$p18 = Product::create([
    'slug' => 'may-hut-bui-dyson-v15-detect',
    'name' => 'Máy Hút Bụi Dyson V15 Detect',
    'description' => 'Máy hút bụi không dây cao cấp, cảm biến bụi laser.',
    'price' => 18990000,
    'stock' => 6,
    'thumbnail' => 'uploads/products/dyson-v15.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p18->id,
    'image_path' => 'uploads/products/dyson-v15.jpg'
]);

$p19 = Product::create([
    'slug' => 'may-hut-bui-electrolux-z1220',
    'name' => 'Máy Hút Bụi Electrolux Z1220',
    'description' => 'Công suất 1600W, hộp chứa bụi dung tích lớn.',
    'price' => 2290000,
    'stock' => 16,
    'thumbnail' => 'uploads/products/electrolux-z1220.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p19->id,
    'image_path' => 'uploads/products/electrolux-z1220.jpg'
]);

$p20 = Product::create([
    'slug' => 'may-loc-khong-khi-sharp-fpj30e',
    'name' => 'Máy Lọc Không Khí Sharp FP-J30E',
    'description' => 'Công nghệ Plasmacluster Ion giúp khử mùi và lọc bụi mịn PM2.5.',
    'price' => 3490000,
    'stock' => 14,
    'thumbnail' => 'uploads/products/sharp-fpj30e.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p20->id,
    'image_path' => 'uploads/products/sharp-fpj30e.jpg'
]);
// ===================================================
// SẢN PHẨM 21 - 30
// ===================================================

$p21 = Product::create([
    'slug' => 'may-loc-khong-khi-xiaomi-smart-air-purifier-4',
    'name' => 'Máy Lọc Không Khí Xiaomi Smart Air Purifier 4',
    'description' => 'Lọc bụi mịn PM2.5, điều khiển từ xa bằng ứng dụng Mi Home.',
    'price' => 4290000,
    'stock' => 18,
    'thumbnail' => 'uploads/products/xiaomi-airpurifier4.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p21->id,
    'image_path' => 'uploads/products/xiaomi-airpurifier4.jpg'
]);

$p22 = Product::create([
    'slug' => 'quat-dieu-hoa-kangaroo-kg50f79',
    'name' => 'Quạt Điều Hòa Kangaroo KG50F79',
    'description' => 'Làm mát nhanh, dung tích bình nước 45L, điều khiển từ xa.',
    'price' => 3790000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/kangaroo-kg50f79.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p22->id,
    'image_path' => 'uploads/products/kangaroo-kg50f79.jpg'
]);

$p23 = Product::create([
    'slug' => 'quat-dung-panasonic-f409kb',
    'name' => 'Quạt Đứng Panasonic F409KB',
    'description' => 'Quạt đứng 3 cánh, vận hành êm, 3 tốc độ gió.',
    'price' => 1590000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/panasonic-f409kb.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p23->id,
    'image_path' => 'uploads/products/panasonic-f409kb.jpg'
]);

$p24 = Product::create([
    'slug' => 'quat-khong-canh-xiaomi-smart-fan-2',
    'name' => 'Quạt Không Cánh Xiaomi Smart Fan 2',
    'description' => 'Điều khiển bằng điện thoại, nhiều chế độ gió tự nhiên.',
    'price' => 2690000,
    'stock' => 20,
    'thumbnail' => 'uploads/products/xiaomi-smartfan2.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p24->id,
    'image_path' => 'uploads/products/xiaomi-smartfan2.jpg'
]);

$p25 = Product::create([
    'slug' => 'ban-ui-hoi-nuoc-tefal-fv1846',
    'name' => 'Bàn Ủi Hơi Nước Tefal FV1846',
    'description' => 'Mặt đế chống dính, hơi nước mạnh, chống nhỏ giọt.',
    'price' => 990000,
    'stock' => 35,
    'thumbnail' => 'uploads/products/tefal-fv1846.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p25->id,
    'image_path' => 'uploads/products/tefal-fv1846.jpg'
]);

$p26 = Product::create([
    'slug' => 'may-say-toc-philips-bhd300',
    'name' => 'Máy Sấy Tóc Philips BHD300',
    'description' => 'Công suất 1600W, chế độ sấy mát bảo vệ tóc.',
    'price' => 790000,
    'stock' => 40,
    'thumbnail' => 'uploads/products/philips-bhd300.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p26->id,
    'image_path' => 'uploads/products/philips-bhd300.jpg'
]);

$p27 = Product::create([
    'slug' => 'may-giat-lg-inverter-fv1409',
    'name' => 'Máy Giặt LG Inverter FV1409 9Kg',
    'description' => 'Công nghệ AI DD™, Inverter tiết kiệm điện, giặt hơi nước.',
    'price' => 10990000,
    'stock' => 12,
    'thumbnail' => 'uploads/products/lg-fv1409.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p27->id,
    'image_path' => 'uploads/products/lg-fv1409.jpg'
]);

$p28 = Product::create([
    'slug' => 'may-giat-samsung-ecobubble-10kg',
    'name' => 'Máy Giặt Samsung EcoBubble 10Kg',
    'description' => 'Công nghệ EcoBubble tạo bong bóng siêu mịn, tiết kiệm điện.',
    'price' => 12490000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/samsung-ecobubble10kg.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p28->id,
    'image_path' => 'uploads/products/samsung-ecobubble10kg.jpg'
]);

$p29 = Product::create([
    'slug' => 'may-say-quan-ao-electrolux-ultimatecare',
    'name' => 'Máy Sấy Quần Áo Electrolux UltimateCare',
    'description' => 'Sấy ngưng tụ, chống nhăn, bảo vệ sợi vải.',
    'price' => 13990000,
    'stock' => 8,
    'thumbnail' => 'uploads/products/electrolux-ultimatecare.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p29->id,
    'image_path' => 'uploads/products/electrolux-ultimatecare.jpg'
]);

$p30 = Product::create([
    'slug' => 'may-nuoc-nong-ariston-slim2',
    'name' => 'Máy Nước Nóng Ariston Slim2 30L',
    'description' => 'Bình chứa tráng men Titan, hệ thống an toàn ELCB.',
    'price' => 3590000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/ariston-slim2.jpg',
    'category_id' => $catClean->id
]);

ProductImage::create([
    'product_id' => $p30->id,
    'image_path' => 'uploads/products/ariston-slim2.jpg'
]);
// ===================================================
// SẢN PHẨM 31 - 40 (NHÀ THÔNG MINH)
// ===================================================

$p31 = Product::create([
    'slug' => 'khoa-cua-dien-tu-xiaomi-smart-lock-e10',
    'name' => 'Khóa Cửa Điện Tử Xiaomi Smart Lock E10',
    'description' => 'Khóa cửa thông minh hỗ trợ vân tay, mật khẩu, thẻ từ và ứng dụng Mi Home.',
    'price' => 4990000,
    'stock' => 12,
    'thumbnail' => 'uploads/products/xiaomi-smart-lock-e10.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p31->id,
    'image_path' => 'uploads/products/xiaomi-smart-lock-e10.jpg'
]);

$p32 = Product::create([
    'slug' => 'khoa-cua-dien-tu-samsung-shp-dp609',
    'name' => 'Khóa Cửa Điện Tử Samsung SHP-DP609',
    'description' => 'Mở khóa bằng vân tay, mã số, thẻ từ và chìa cơ.',
    'price' => 7690000,
    'stock' => 10,
    'thumbnail' => 'uploads/products/samsung-shp-dp609.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p32->id,
    'image_path' => 'uploads/products/samsung-shp-dp609.jpg'
]);

$p33 = Product::create([
    'slug' => 'camera-imou-ranger-2',
    'name' => 'Camera IMOU Ranger 2',
    'description' => 'Camera Full HD 1080P, xoay 360 độ, phát hiện chuyển động.',
    'price' => 890000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/imou-ranger2.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p33->id,
    'image_path' => 'uploads/products/imou-ranger2.jpg'
]);

$p34 = Product::create([
    'slug' => 'camera-ezviz-c6n',
    'name' => 'Camera EZVIZ C6N',
    'description' => 'Camera WiFi quay quét 360°, đàm thoại hai chiều.',
    'price' => 990000,
    'stock' => 25,
    'thumbnail' => 'uploads/products/ezviz-c6n.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p34->id,
    'image_path' => 'uploads/products/ezviz-c6n.jpg'
]);

$p35 = Product::create([
    'slug' => 'camera-tplink-tapo-c200',
    'name' => 'Camera TP-Link Tapo C200',
    'description' => 'Camera an ninh Full HD, quay ngang 360°, hỗ trợ thẻ nhớ 128GB.',
    'price' => 790000,
    'stock' => 35,
    'thumbnail' => 'uploads/products/tapo-c200.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p35->id,
    'image_path' => 'uploads/products/tapo-c200.jpg'
]);

$p36 = Product::create([
    'slug' => 'o-cam-thong-minh-xiaomi-mi-smart-plug',
    'name' => 'Ổ Cắm Thông Minh Xiaomi Mi Smart Plug',
    'description' => 'Điều khiển bật/tắt thiết bị từ xa qua ứng dụng Mi Home.',
    'price' => 390000,
    'stock' => 50,
    'thumbnail' => 'uploads/products/xiaomi-smart-plug.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p36->id,
    'image_path' => 'uploads/products/xiaomi-smart-plug.jpg'
]);

$p37 = Product::create([
    'slug' => 'o-cam-thong-minh-tplink-tapo-p100',
    'name' => 'Ổ Cắm Thông Minh TP-Link Tapo P100',
    'description' => 'Hẹn giờ, điều khiển từ xa và quản lý điện năng.',
    'price' => 350000,
    'stock' => 40,
    'thumbnail' => 'uploads/products/tapo-p100.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p37->id,
    'image_path' => 'uploads/products/tapo-p100.jpg'
]);

$p38 = Product::create([
    'slug' => 'den-thong-minh-philips-hue-white',
    'name' => 'Đèn LED Thông Minh Philips Hue White',
    'description' => 'Điều chỉnh độ sáng qua điện thoại, tương thích Google Home và Alexa.',
    'price' => 890000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/philips-hue-white.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p38->id,
    'image_path' => 'uploads/products/philips-hue-white.jpg'
]);

$p39 = Product::create([
    'slug' => 'cam-bien-cua-aqara-mccgq11lm',
    'name' => 'Cảm Biến Cửa Aqara MCCGQ11LM',
    'description' => 'Phát hiện đóng/mở cửa, gửi thông báo đến điện thoại.',
    'price' => 450000,
    'stock' => 40,
    'thumbnail' => 'uploads/products/aqara-door-sensor.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p39->id,
    'image_path' => 'uploads/products/aqara-door-sensor.jpg'
]);

$p40 = Product::create([
    'slug' => 'cam-bien-chuyen-dong-aqara-rtcgq11lm',
    'name' => 'Cảm Biến Chuyển Động Aqara RTCGQ11LM',
    'description' => 'Phát hiện chuyển động, tự động kích hoạt các thiết bị thông minh.',
    'price' => 520000,
    'stock' => 35,
    'thumbnail' => 'uploads/products/aqara-motion-sensor.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p40->id,
    'image_path' => 'uploads/products/aqara-motion-sensor.jpg'
]);
// ===================================================
// SẢN PHẨM 41 - 50 (NHÀ THÔNG MINH)
// ===================================================

$p41 = Product::create([
    'slug' => 'chuong-cua-thong-minh-xiaomi-doorbell-3',
    'name' => 'Chuông Cửa Thông Minh Xiaomi Doorbell 3',
    'description' => 'Chuông cửa có camera Full HD, đàm thoại hai chiều, phát hiện chuyển động AI.',
    'price' => 1690000,
    'stock' => 20,
    'thumbnail' => 'uploads/products/xiaomi-doorbell-3.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p41->id,
    'image_path' => 'uploads/products/xiaomi-doorbell-3.jpg'
]);

$p42 = Product::create([
    'slug' => 'google-nest-mini-gen-2',
    'name' => 'Loa Thông Minh Google Nest Mini Gen 2',
    'description' => 'Điều khiển bằng giọng nói với Google Assistant, kết nối nhà thông minh.',
    'price' => 1290000,
    'stock' => 25,
    'thumbnail' => 'uploads/products/google-nest-mini-2.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p42->id,
    'image_path' => 'uploads/products/google-nest-mini-2.jpg'
]);

$p43 = Product::create([
    'slug' => 'amazon-echo-dot-gen-5',
    'name' => 'Loa Thông Minh Amazon Echo Dot Gen 5',
    'description' => 'Tích hợp Alexa, phát nhạc, điều khiển thiết bị thông minh.',
    'price' => 1690000,
    'stock' => 18,
    'thumbnail' => 'uploads/products/amazon-echo-dot-5.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p43->id,
    'image_path' => 'uploads/products/amazon-echo-dot-5.jpg'
]);

$p44 = Product::create([
    'slug' => 'tv-box-xiaomi-mi-box-s-2nd-gen',
    'name' => 'TV Box Xiaomi Mi Box S 2nd Gen',
    'description' => 'Hỗ trợ 4K HDR, Dolby Audio, hệ điều hành Google TV.',
    'price' => 1890000,
    'stock' => 30,
    'thumbnail' => 'uploads/products/xiaomi-mi-box-s.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p44->id,
    'image_path' => 'uploads/products/xiaomi-mi-box-s.jpg'
]);

$p45 = Product::create([
    'slug' => 'may-chieu-wanbo-t2-max',
    'name' => 'Máy Chiếu Mini Wanbo T2 Max',
    'description' => 'Máy chiếu Full HD nhỏ gọn, phù hợp giải trí tại gia.',
    'price' => 4290000,
    'stock' => 12,
    'thumbnail' => 'uploads/products/wanbo-t2-max.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p45->id,
    'image_path' => 'uploads/products/wanbo-t2-max.jpg'
]);

$p46 = Product::create([
    'slug' => 'cong-tac-thong-minh-broadlink-tc2',
    'name' => 'Công Tắc Thông Minh BroadLink TC2',
    'description' => 'Công tắc cảm ứng điều khiển từ xa qua ứng dụng BroadLink.',
    'price' => 590000,
    'stock' => 35,
    'thumbnail' => 'uploads/products/broadlink-tc2.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p46->id,
    'image_path' => 'uploads/products/broadlink-tc2.jpg'
]);

$p47 = Product::create([
    'slug' => 'bo-dieu-khien-hong-ngoai-broadlink-rm4-mini',
    'name' => 'Bộ Điều Khiển Hồng Ngoại BroadLink RM4 Mini',
    'description' => 'Điều khiển TV, máy lạnh và các thiết bị hồng ngoại bằng điện thoại.',
    'price' => 890000,
    'stock' => 28,
    'thumbnail' => 'uploads/products/broadlink-rm4-mini.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p47->id,
    'image_path' => 'uploads/products/broadlink-rm4-mini.jpg'
]);

$p48 = Product::create([
    'slug' => 'rem-cua-thong-minh-aqara-curtain-driver',
    'name' => 'Động Cơ Rèm Cửa Thông Minh Aqara Curtain Driver',
    'description' => 'Tự động đóng mở rèm theo lịch hoặc điều khiển bằng ứng dụng.',
    'price' => 2590000,
    'stock' => 15,
    'thumbnail' => 'uploads/products/aqara-curtain-driver.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p48->id,
    'image_path' => 'uploads/products/aqara-curtain-driver.jpg'
]);

$p49 = Product::create([
    'slug' => 'cam-bien-nhiet-do-do-am-aqara-wsdcgq11lm',
    'name' => 'Cảm Biến Nhiệt Độ & Độ Ẩm Aqara WSDCGQ11LM',
    'description' => 'Theo dõi nhiệt độ và độ ẩm trong nhà theo thời gian thực.',
    'price' => 450000,
    'stock' => 45,
    'thumbnail' => 'uploads/products/aqara-temperature-sensor.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p49->id,
    'image_path' => 'uploads/products/aqara-temperature-sensor.jpg'
]);

$p50 = Product::create([
    'slug' => 'aqara-hub-m2',
    'name' => 'Trung Tâm Điều Khiển Aqara Hub M2',
    'description' => 'Bộ điều khiển trung tâm kết nối các thiết bị Aqara và Apple HomeKit.',
    'price' => 1490000,
    'stock' => 20,
    'thumbnail' => 'uploads/products/aqara-hub-m2.jpg',
    'category_id' => $catSmart->id
]);

ProductImage::create([
    'product_id' => $p50->id,
    'image_path' => 'uploads/products/aqara-hub-m2.jpg'
]);


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