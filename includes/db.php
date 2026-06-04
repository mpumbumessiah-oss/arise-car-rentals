<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

class Database
{
    private static ?Database $instance = null;
    private mysqli $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            die("DB Connection Failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connection(): mysqli
    {
        return $this->connection;
    }

    /**
     * SAFE QUERY EXECUTION
     */
    public function query(string $sql, array $params = []): mysqli_stmt
    {
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            die("SQL Prepare Error: " . $this->connection->error);
        }

        if (!empty($params)) {
            // dynamic type detection
            $types = '';
            foreach ($params as $param) {
                $types .= match (gettype($param)) {
                    'integer' => 'i',
                    'double'  => 'd',
                    'boolean' => 'i',
                    default   => 's',
                };
            }

            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("SQL Execute Error: " . $stmt->error);
        }

        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function insert(string $sql, array $params = []): int
    {
        $this->query($sql, $params);
        return $this->connection->insert_id;
    }

    public function update(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->affected_rows;
    }
}

/**
 * Helper function (clean access)
 */
function db(): Database
{
    return Database::getInstance();
}