<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('password_resets');
    Capsule::schema()->create('password_resets', function ($table) {
    $table->string('email')->index();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});
echo "[✓] Tạo bảng 'password_resets' thành công!\n";
