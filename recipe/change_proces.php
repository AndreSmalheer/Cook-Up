<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../includes/dbh.inc.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    echo "Invalid token.";
    exit;
}

$recipeId    = trim($_POST["id"]);
$title       = trim($_POST["title"]);
$description = trim($_POST["description"]);
$categoryId  = trim($_POST["categoryId"]);

$tags        = $_POST["tags"];
$ingredients = $_POST["ingredients"];
$steps       = $_POST["steps"];

// Validatie
$errors = [];

if (empty($title)) {
    $errors['title'] = "Title may not be empty.";
}
if (empty($description)) {
    $errors['description'] = "Description may not be empty.";
}
if (empty($categoryId)) {
    $errors['categoryId'] = "Category is required.";
}

if (!empty($errors)) {
    $_SESSION['fouten'] = $errors;
    header("Location: ./change_recipe.php?id=" . $recipeId);
    exit;
}

// recipe updatee
try {

    $stmt = $conn->prepare("
        UPDATE recipes
        SET title = :title,
            description = :description,
            categoryId = :categoryId
        WHERE recipeId = :recipeId
    ");

    $stmt->execute([
        ":title"       => $title,
        ":description" => $description,
        ":categoryId"  => $categoryId,
        ":recipeId"    => $recipeId
    ]);

} catch (PDOException $e) {
    echo "Error updating recipe: " . $e->getMessage();
    exit;
}

// update tags
try {
    $conn->beginTransaction();

    // Verwijderen
    $stmt = $conn->prepare("DELETE FROM recipe_tags WHERE recipeId = ?");
    $stmt->execute([$recipeId]);

    // Toevoegen
    if (!empty($tags)) {
        $stmt = $conn->prepare("INSERT INTO recipe_tags (recipeId, tagId) VALUES (?, ?)");

        foreach ($tags as $tagId) {
            $stmt->execute([$recipeId, $tagId]);
        }
    }

    $conn->commit();

} catch (PDOException $e) {
    echo "Error updating tags: " . $e->getMessage();
    exit;
}

// ingredienten update
$stmt = $conn->prepare("SELECT ingredientId FROM ingredients WHERE recipeId = ?");
$stmt->execute([$recipeId]);
$existing = $stmt->fetchAll(PDO::FETCH_COLUMN);

$formIngredientIds = [];

foreach ($ingredients as $i) {
    if (!empty($i["id"])) {
        $formIngredientIds[] = $i["id"];
    }
}

// Verwijderen
foreach ($existing as $oldId) {
    if (!in_array($oldId, $formIngredientIds)) {
        $stmt = $conn->prepare("DELETE FROM ingredients WHERE ingredientId = ?");
        $stmt->execute([$oldId]);
    }
}

// Update/Insert
foreach ($ingredients as $ing) {
    if (!empty($ing["id"])) {

        $stmt = $conn->prepare("
            UPDATE ingredients
            SET name = :name, quantity = :quantity
            WHERE ingredientId = :id
        ");

        $stmt->execute([
            ":name"     => $ing["name"],
            ":quantity" => $ing["quantity"],
            ":id"       => $ing["id"]
        ]);

    } else {

        $stmt = $conn->prepare("
            INSERT INTO ingredients (recipeId, name, quantity)
            VALUES (:recipeId, :name, :quantity)
        ");

        $stmt->execute([
            ":recipeId" => $recipeId,
            ":name"     => $ing["name"],
            ":quantity" => $ing["quantity"]
        ]);
    }
}

// steps update
$stmt = $conn->prepare("SELECT stepId FROM steps WHERE recipeId = ?");
$stmt->execute([$recipeId]);
$existingSteps = $stmt->fetchAll(PDO::FETCH_COLUMN);

$formStepIds = [];

foreach ($steps as $step) {
    if (!empty($step["id"])) {
        $formStepIds[] = $step["id"];
    }
}

// Verwijderen
foreach ($existingSteps as $oldId) {
    if (!in_array($oldId, $formStepIds)) {
        $stmt = $conn->prepare("DELETE FROM steps WHERE stepId = ?");
        $stmt->execute([$oldId]);
    }
}

// Insert / Update
$number = 1;

foreach ($steps as $st) {

    if (!empty($st["id"])) {

        $stmt = $conn->prepare("
            UPDATE steps
            SET instruction = :instruction,
                stepNumber = :stepNumber
            WHERE stepId = :id
        ");

        $stmt->execute([
            ":instruction" => $st["instruction"],
            ":stepNumber"  => $number,
            ":id"          => $st["id"]
        ]);

    } else {

        $stmt = $conn->prepare("
            INSERT INTO steps (recipeId, instruction, stepNumber)
            VALUES (:recipeId, :instruction, :stepNumber)
        ");

        $stmt->execute([
            ":recipeId"   => $recipeId,
            ":instruction" => $st["instruction"],
            ":stepNumber"  => $number
        ]);
    }

    $number++;
}

header("Location: ../profile.php");
exit;