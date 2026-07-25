<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('order_items');
    Capsule::schema()->create('order_items', function ($table) {
    $table->increments('id');
    $table->integer('order_id')->unsigned();
    $table->integer('product_id')->unsigned();
    $table->integer('quantity');
    $table->decimal('price', 15, 2); // Lưu giá đóng băng tại thời điểm mua

    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'order_items' thành công!\n";
