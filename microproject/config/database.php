<?php
// config/database.php

$host = '127.0.0.1';
$db   = 'ems';
$user = 'root'; // Change if required
$pass = '';     // Change if required
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a production app, we would log this instead of outputting.
    // However for a microproject, it helps with debugging on startup.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
