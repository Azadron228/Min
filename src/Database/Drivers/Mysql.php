<?php

namespace Min\Database\Drivers;

use mysqli;

class Mysql {
    private $config;
    private $connection;

    public function __construct($config) {
        $this->config = $config;
    }

    public function connect() {
        $host = $this->config['host'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $database = $this->config['database'];

        $this->connection = new mysqli($host, $username, $password, $database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }
}
