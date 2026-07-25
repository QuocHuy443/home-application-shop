<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('orders');
    Capsule::schema()->create('orders', function ($table) {
    $table->increments('id');
    $table->string('order_code')->unique();
    $table->integer('user_id')->unsigned();
    $table->decimal('total_amount', 15, 2);
    $table->string('shipping_name');
    $table->string('shipping_phone');
    $table->text('shipping_address');
    $table->string('status')->default('pending');
    $table->text('note')->nullable();
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'orders' thành công!\n";
