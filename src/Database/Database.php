<?php

namespace Min\Database;

use Min\Database\Drivers\Mysql;
use Min\Database\Drivers\Sqlite;

class Database
{
    private $connection;

    public function connect($config)
    {
        $driver = $config['driver'];

        switch ($driver) {
            case 'mysql':
                $this->connection = new Mysql($config);
                break;
            case 'sqlite':
                $this->connection = new Sqlite($config);
                break;
            default:
                throw new Exception("Unsupported database driver: $driver");
        }

        $this->connection->connect();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }
}
