<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\OrderController;

echo "===============================================\n";
echo "===     TEST CHỨC NĂNG QUẢN TRỊ ADMIN       ===\n";
echo "===============================================\n\n";

$catController     = new CategoryController();
$productController = new ProductController();
$orderController   = new OrderController();

// 1. TEST TẠO DANH MỤC MỚI
echo "[1] Test Tạo Danh Mục...\n";
$catResult = $catController->store([
    'name' => 'Lò Vi Sóng & Nồi Chiên ' . rand(10, 99)
]);
echo "    -> " . $catResult['message'] . "\n\n";

$catId = $catResult['data']->id ?? 1;

// 2. TEST TẠO SẢN PHẨM MỚI
echo "[2] Test Tạo Sản Phẩm...\n";
$prodResult = $productController->store([
    'name'        => 'Nồi Chiên Không Dầu Lock&Lock 5.5L ' . rand(10, 99),
    'price'       => 1850000,
    'stock'       => 15,
    'description' => 'Công nghệ Chiên Không Dầu 360 độ, giảm 80% chất béo.',
    'category_id' => $catId
]);
echo "    -> " . $prodResult['message'] . "\n\n";

// 3. TEST XEM DANH SÁCH SẢN PHẨM
echo "[3] Test Lấy Danh Sách Sản Phẩm Trong CSDL...\n";
$products = $productController->index();
echo "    -> Tổng số sản phẩm hiện tại: " . count($products) . " sản phẩm.\n";
foreach ($products->take(3) as $p) {
    echo "       + [" . $p->id . "] " . $p->name . " | Giá: " . number_format($p->price) . " VNĐ | Danh mục: " . ($p->category->name ?? 'N/A') . "\n";
}
echo "\n";

// 4. TEST LẤY DANH SÁCH ĐƠN HÀNG
echo "[4] Test Lấy Danh Sách Đơn Hàng...\n";
$orders = $orderController->index();
echo "    -> Tổng số đơn hàng trong hệ thống: " . count($orders) . " đơn.\n";

echo "===============================================\n";
echo "===    HOÀN THÀNH KIỂM THỬ ADMIN LOGIC      ===\n";
echo "===============================================\n";