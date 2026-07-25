<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('roles');
    Capsule::schema()->create('roles', function ($table) {
    $table->increments('id');
    $table->string('name')->unique();
    $table->string('description')->nullable();
    $table->timestamps();
});
echo "[✓] Tạo bảng 'roles' thành công!\n";
