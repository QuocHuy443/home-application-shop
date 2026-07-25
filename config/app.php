<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'HomeApp Shop',
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'timezone' => 'Asia/Ho_Chi_Minh',
];
