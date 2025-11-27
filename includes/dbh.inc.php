<?php
// Foutmeldingen aanzetten
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pad naar je lokale databasebestand
$dbFile = __DIR__ . "/recipe_platform.db";

try {
    // SQLite DSN (Data Source Name)
    $dsn = "sqlite:" . $dbFile;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $conn = new PDO($dsn, null, null, $options);

    // Optioneel: check of de tabel bestaat, anders aanmaken
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            password TEXT NOT NULL
        )
    ");

} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}