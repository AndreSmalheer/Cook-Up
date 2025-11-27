<?php

// Errors tonen
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sessie starten
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

$usersId = $_SESSION['userid'];

require '../includes/dbh.inc.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

$title       = trim($_POST['title']);
$description = trim($_POST['description']);
$categoryId  = trim($_POST['categoryId']);
$imagePath   = trim($_POST['imagePath']);

$tags        = $_POST['tags'];
$ingredients = $_POST['ingredient_name'];
$quantities  = $_POST['ingredient_quantity'];
$steps       = $_POST['step_instruction'];

// Fouten array
$fouten = [];

// Validatie
if (empty($title)) {
    $fouten['title'] = "Title is empty.";
}

if (empty($description)) {
    $fouten['description'] = "Description is empty.";
}

if (empty($categoryId)) {
    $fouten['categoryId'] = "Category is required.";
}

if (!empty($fouten)) {
    $_SESSION['fouten'] = $fouten;
    header("Location: ./add_recipe.php");
    exit;
}

try {

    $conn->beginTransaction();

    // Recipe insert
    $stmt = $conn->prepare("
        INSERT INTO recipes (usersId, title, description, categoryId, imagePath)
        VALUES (:usersId, :title, :description, :categoryId, :imagePath)
    ");

    $stmt->execute([
        ":usersId"    => $usersId,
        ":title"      => $title,
        ":description" => $description,
        ":categoryId" => $categoryId,
        ":imagePath"  => $imagePath
    ]);

    $recipeId = $conn->lastInsertId();

    // ingredients
    $stmtIng = $conn->prepare("
        INSERT INTO ingredients (recipeId, name, quantity)
        VALUES (:recipeId, :name, :quantity)
    ");

    for ($i = 0; $i < count($ingredients); $i++) {

        if (!empty(trim($ingredients[$i]))) {

            $stmtIng->execute([
                ":recipeId" => $recipeId,
                ":name"     => $ingredients[$i],
                ":quantity" => $quantities[$i]
            ]);
        }
    }

    // Steps
    $stmtStep = $conn->prepare("
        INSERT INTO steps (recipeId, stepNumber, instruction)
        VALUES (:recipeId, :stepNumber, :instruction)
    ");

    for ($i = 0; $i < count($steps); $i++) {

        if (!empty(trim($steps[$i]))) {

            $stmtStep->execute([
                ":recipeId"   => $recipeId,
                ":stepNumber" => $i + 1,
                ":instruction" => $steps[$i]
            ]);
        }
    }

    // Tags
    if (!empty($tags)) {

        $stmtTag = $conn->prepare("
            INSERT INTO recipe_tags (recipeId, tagId)
            VALUES (:recipeId, :tagId)
        ");

        foreach ($tags as $tagId) {
            $stmtTag->execute([
                ":recipeId" => $recipeId,
                ":tagId"    => $tagId
            ]);
        }
    }

    $conn->commit();
    header("Location: ../profile.php?success=recipe_added");
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}