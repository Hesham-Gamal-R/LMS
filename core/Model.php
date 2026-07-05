<?php
abstract class Model {
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): array|false {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = $id")->fetch();
    }

    public function findAll(string $where = '', string $orderBy = ''): array {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        
        return $this->db->query($sql)->fetchAll();
    }

    public function findOne(string $where): array|false {
        return $this->db->query("SELECT * FROM {$this->table} WHERE $where LIMIT 1")->fetch();
    }

    public function insert(array $data): int {
        $cols = implode(', ', array_keys($data));
        $vals = "'" . implode("', '", array_values($data)) . "'";
        
        $this->db->exec("INSERT INTO {$this->table} ($cols) VALUES ($vals)");
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', '); 

        return (bool)$this->db->exec("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = $id");
    }

    public function delete(int $id): bool {
        return (bool)$this->db->exec("DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id");
    }

    public function count(string $where = ''): int {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        
        return (int)$this->db->query($sql)->fetchColumn();
    }

    public function query(string $sql): array {
        return $this->db->query($sql)->fetchAll();
    }

    public function queryOne(string $sql): array|false {
        return $this->db->query($sql)->fetch();
    }

    public function execute(string $sql): bool {
        return (bool)$this->db->exec($sql);
    }
}
