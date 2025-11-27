<?php
// Foutmeldingen aanzetten
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database gegevens
$dbServername = "localhost";
$dbUsername = "bp102896";
$dbPassword = "Adamsaber0182";
$dbDatabase = "loginSysteem_bp01";

try {
    $dsn = "mysql:host={$dbServername};dbname={$dbDatabase};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $conn = new PDO($dsn, $dbUsername, $dbPassword, $options);

} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}