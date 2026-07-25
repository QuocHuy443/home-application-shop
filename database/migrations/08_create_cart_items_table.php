<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('cart_items');
    Capsule::schema()->create('cart_items', function ($table) {
    $table->increments('id');
    $table->integer('cart_id')->unsigned();
    $table->integer('product_id')->unsigned();
    $table->integer('quantity')->default(1);
    $table->timestamps();

    $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'cart_items' thành công!\n";
