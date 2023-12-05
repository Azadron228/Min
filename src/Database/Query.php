<?php

use Min\Database\Database;

class Query {
    protected $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function select($table, $columns = '*', $where = '', $params = []) {
        $sql = "SELECT {$columns} FROM {$table}";

        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }

        $statement = $this->database->query($sql, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

        return $this->database->query($sql, array_values($data));
    }

    public function update($table, $data, $where = '', $params = []) {
        $setClause = implode(', ', array_map(function ($column) {
            return "$column = ?";
        }, array_keys($data)));

        $sql = "UPDATE {$table} SET {$setClause}";

        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }

        return $this->database->query($sql, array_merge(array_values($data), $params));
    }

    public function delete($table, $where = '', $params = []) {
        $sql = "DELETE FROM {$table}";

        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }

        return $this->database->query($sql, $params);
    }
}
