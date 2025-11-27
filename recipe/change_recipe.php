<?php

// Connectie maken met de database
require '../includes/dbh.inc.php';


// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

$id = $_GET['id']; // Haal de id op uit de url

function getRecipeData($id)
{
    global $conn;

    // 1. Recept
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE recipeId = :id");
    $stmt->execute(['id' => $id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Ingredienten
    $stmt = $conn->prepare("SELECT * FROM ingredients WHERE recipeId = :id");
    $stmt->execute(['id' => $id]);
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Stappen
    $stmt = $conn->prepare("SELECT * FROM steps WHERE recipeId = :id ORDER BY stepNumber ASC");
    $stmt->execute(['id' => $id]);
    $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Tags
    $stmt = $conn->prepare("SELECT tagId FROM recipe_tags WHERE recipeId = :id");
    $stmt->execute(['id' => $id]);
    $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return [
        'recipe' => $recipe,
        'ingredients' => $ingredients,
        'steps' => $steps,
        'tags' => $tags
    ];
}


$resultaat = getRecipeData($id);
include './change_inc_recipe.php';