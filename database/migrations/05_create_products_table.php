<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('products');
    Capsule::schema()->create('products', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 15, 2);
    $table->integer('stock')->default(0);
    $table->string('thumbnail')->nullable();
    $table->integer('category_id')->unsigned();
    $table->timestamps();

    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'products' thành công!\n";
