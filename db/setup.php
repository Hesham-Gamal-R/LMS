<?php
    require_once 'config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);

    $sql = file_get_contents('db/schema.sql');

    $statements = array_filter(array_map(trim(...), explode(';', $sql)));

    foreach ($statements as $stmt) {
    $pdo->exec($stmt);
    }

    echo "Database and tables created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
