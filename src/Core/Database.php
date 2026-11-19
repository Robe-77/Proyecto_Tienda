<?php

declare(strict_types=1);

namespace App\Core;

use mysqli;
use RuntimeException;

class Database
{
    private static ?mysqli $connection = null;

    public static function getConnection(): mysqli
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $config = require BASE_PATH . '/config/database.php';

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $conn = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port']
        );

        if ($conn->connect_error) {
            throw new RuntimeException('Error de conexión a la base de datos: ' . $conn->connect_error);
        }

        $conn->set_charset($config['charset']);
        self::$connection = $conn;

        return self::$connection;
    }
}
