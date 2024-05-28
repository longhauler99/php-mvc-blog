<?php

namespace App\Core;
use PDO;
use PDOException;

class Connection
{
    public static $connection = false;

    private function __construct()
    {

    }

    public static function connect($config)
    {
        try {
            if (!self::$connection) {
                $conn = new PDO("mysql:host={$config['server']};dbname={$config['dbname']}", $config['username'], $config['password']);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$connection = $conn;
                return self::$connection;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }
}