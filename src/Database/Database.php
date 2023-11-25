<?php

namespace Min\Database;

use Exception;
use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        $this->loadEnv();
        $this->connect();
    }

    private function loadEnv()
    {
        $envFile = '/home/aza/MinProject/app/.env';

        if (!file_exists($envFile)) {
            throw new Exception('.env file not found.');
        }

        $env = parse_ini_file($envFile);

        if ($env === false) {
            throw new Exception('Error parsing .env file.');
        }

        foreach ($env as $key => $value) {
            putenv("$key=$value");
        }
    }

    public function connect()
    {
        $driver = getenv('DB_DRIVER');
        $host = getenv('DB_HOST');
        $database = getenv('DB_DATABASE');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        try {
            if ($driver === 'mysql') {
                $this->pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            } elseif ($driver === 'sqlite') {
                if (!file_exists($database)) {
                    touch($database);
                }
                $this->pdo = new PDO("sqlite:$database");
            } else {
                throw new Exception('Invalid database driver specified in .env');
            }

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Database connection error: ' . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            throw new Exception('Query error: ' . $e->getMessage());
        }
    }
}
