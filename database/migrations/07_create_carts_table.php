<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('carts');
    Capsule::schema()->create('carts', function ($table) {
    $table->increments('id');
    // Khóa ngoại user_id có unique() để đảm bảo quan hệ 1-1
    $table->integer('user_id')->unsigned()->unique();
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'carts' thành công!\n";
