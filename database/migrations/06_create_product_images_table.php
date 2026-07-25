<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('product_images');
    Capsule::schema()->create('product_images', function ($table) {
    $table->increments('id');
    $table->integer('product_id')->unsigned();
    $table->string('image_path');
    $table->timestamps();

    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'product_images' thành công!\n";
