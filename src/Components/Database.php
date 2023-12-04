<?php

namespace Components;

class Database
{
    private $pdo = null;
    static $db = null;

    public function __construct()
    {
        if (self::$db !== null) {
            throw new \Exception('You may not instantiate more than one DB instance.');
        }

        $host = 'db';
        $db   = 'user_crud';
        $user = 'user';
        $pass = 'password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new \PDO($dsn, $user, $pass, $opt);
    }

    // Get DB singleton
    public static function getInstance(): Database
    {
        if (self::$db === null) {
            self::$db = new Database();
        }
        return self::$db;
    }

    public function query(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function insert(string $table, array $fields): int
    {
        $columns = implode(', ', array_keys($fields));
        $values = implode(', :', array_keys($fields));
        $query = "INSERT INTO $table ($columns) VALUES (:$values)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($fields);
        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $fields, string $where, array $params = []): int
    {
        $set = '';
        foreach ($fields as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');
        $query = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_merge($fields, $params));
        return $stmt->rowCount();
    }

    public function updateById(string $table, array $fields, int $id): int
    {
        return $this->update($table, $fields, 'id = :id', ['id' => $id]);
    }

    public function deleteById(string $table, int $id): int
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
