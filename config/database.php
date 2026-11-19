<?php

declare(strict_types=1);

return [
    'host' => env('DB_HOST', 'localhost'),
    'port' => (int) env('DB_PORT', '3306'),
    'database' => env('DB_NAME', 'proyecto_fbd'),
    'username' => env('DB_USER', 'root'),
    'password' => env('DB_PASS', ''),
    'charset' => env('DB_CHARSET', 'utf8mb4'),
];
