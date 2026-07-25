<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

echo "--- BẮT ĐẦU TẠO CƠ SỞ DỮ LIỆU ---\n\n";

Capsule::statement('SET FOREIGN_KEY_CHECKS = 0;');

$files = glob(__DIR__ . '/migrations/*.php');
foreach ($files as $file) {
    require_once $file;
}

Capsule::statement('SET FOREIGN_KEY_CHECKS = 1;');

echo "\n===========================================\n";
echo ">>> KHỞI TẠO CƠ SỞ DỮ LIỆU THÀNH CÔNG! <<<\n";
echo "===========================================\n";
