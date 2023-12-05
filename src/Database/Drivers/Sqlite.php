<?php

namespace Min\Database\Drivers;

use SQLite3;

class Sqlite {
    private $config;
    private $connection;

    public function __construct($config) {
        $this->config = $config;
    }

    public function connect() {
        $databaseFile = $this->config['database'];

        $this->connection = new SQLite3($databaseFile);

        if (!$this->connection) {
            die("Connection failed: Unable to open database file");
        }
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }
}
