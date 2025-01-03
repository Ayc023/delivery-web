<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'BSBA');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Error handling
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
