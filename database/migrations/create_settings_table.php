<?php
use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('settings')) {
    Capsule::schema()->create('settings', function ($table) {
        $table->increments('id');
        $table->string('key_name')->unique();
        $table->text('key_value')->nullable();
        $table->timestamps();
    });

    // Thêm dữ liệu mặc định ban đầu
    Capsule::table('settings')->insert([
        ['key_name' => 'site_name', 'key_value' => 'HomeApp Shop'],
        ['key_name' => 'site_hotline', 'key_value' => '1900 8888'],
        ['key_name' => 'site_email', 'key_value' => 'support@homeapp.vn'],
        ['key_name' => 'primary_color', '#0d6efd'],
        ['key_name' => 'announcement', 'key_value' => 'Chào mừng bạn đến với HomeApp Shop!']
    ]);
}