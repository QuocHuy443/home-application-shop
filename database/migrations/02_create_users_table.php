<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('users');
    Capsule::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->integer('role_id')->unsigned();
    $table->string('remember_token', 100)->nullable();
    $table->timestamps();

    // Ràng buộc khóa ngoại với bảng roles
    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'users' thành công!\n";
