<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasColumn('users', 'status')) {
    Capsule::schema()->table('users', function ($table) {
        $table->tinyInteger('status')->default(1)->after('role_id');
    });
}