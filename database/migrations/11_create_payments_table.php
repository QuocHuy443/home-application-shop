<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('payments');
    Capsule::schema()->create('payments', function ($table) {
    $table->increments('id');
    $table->integer('order_id')->unsigned()->unique();
    $table->string('payment_method');
    $table->string('payment_status')->default('unpaid');
    $table->string('transaction_id')->nullable();
    $table->timestamps();

    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
});
echo "[✓] Tạo bảng 'payments' thành công!\n";
