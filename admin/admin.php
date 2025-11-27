<?php
// Connectie maken met de database
require '../includes/dbh.inc.php';
global $conn;

// Kijken of de gebruiker een admin is
require '../includes/auth_admin.inc.php';

try {
    $queryUsers = "SELECT * FROM users";
    $stmtUsers = $conn->prepare($queryUsers);
    $stmtUsers->execute();
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Query error: ' . $e->getMessage());
}

try {
    $queryRecipes = "SELECT * FROM recipes";
    $stmtRecipes = $conn->prepare($queryRecipes);
    $stmtRecipes->execute();
    $recipes = $stmtRecipes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Query error: ' . $e->getMessage());
}

// include de view
include '../includes/admin.inc.php';