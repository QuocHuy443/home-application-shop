<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('categories');
    Capsule::schema()->create('categories', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->string('slug')->unique();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
echo "[✓] Tạo bảng 'categories' thành công!\n";
