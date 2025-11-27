<?php

// Sessie starten
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database verbinding
require './includes/dbh.inc.php';
global $conn;

$data = [];

try {

    // Alle recepten ophalen
    $sql = "SELECT recipeId, usersId, title, description, categoryId, imagePath, createdAt, updatedAt 
            FROM recipes 
            ORDER BY createdAt DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Elk recept verwerken
    foreach ($recipes as $recipe) {

        $recipeId = $recipe['recipeId'];

        // INGREDIENTS OPHALEN
        $stmtIng = $conn->prepare("
            SELECT name, quantity 
            FROM ingredients 
            WHERE recipeId = ?
            ORDER BY ingredientId ASC
        ");

        $stmtIng->execute([$recipeId]);
        $ingredientRows = $stmtIng->fetchAll(PDO::FETCH_ASSOC);

        $ingredients = [];

        foreach ($ingredientRows as $row) {

            $ingredientString = "";

            if (!empty($row['quantity'])) {
                $ingredientString = $row['quantity'] . " " . $row['name'];
            } else {
                $ingredientString = $row['name'];
            }

            $ingredients[] = $ingredientString;
        }

        // STEPS OPHALEN
        $stmtSteps = $conn->prepare("
            SELECT stepNumber, instruction 
            FROM steps 
            WHERE recipeId = ?
            ORDER BY stepNumber ASC
        ");

        $stmtSteps->execute([$recipeId]);
        $stepsRows = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);

        $steps = [];

        foreach ($stepsRows as $stepRow) {
            $steps[] = [
                "stepNumber"   => $stepRow["stepNumber"],
                "instructions" => $stepRow["instruction"]
            ];
        }

        // TAGS OPHALEN
        $stmtTags = $conn->prepare("
            SELECT t.name
            FROM tags t
            JOIN recipe_tags rt ON rt.tagId = t.tagId
            WHERE rt.recipeId = ?
            ORDER BY t.name ASC
        ");

        $stmtTags->execute([$recipeId]);
        $tagRows = $stmtTags->fetchAll(PDO::FETCH_COLUMN);

        // lowercase tags
        $tags = [];
        foreach ($tagRows as $tag) {
            $tags[] = strtolower($tag);
        }

        // IMAGE PAD FALLBACK
        $img = "recepten/placeholder.jpg";

        if (!empty($recipe["imagePath"])) {
            $img = $recipe["imagePath"];
        }

        $data[] = [
            "id"          => (string)$recipeId,
            "category_id" => (string)$recipe["categoryId"],
            "title"       => $recipe["title"],
            "img"         => $img,
            "description" => $recipe["description"],
            "tags"        => $tags,
            "steps"       => $steps,
            "ingredients" => $ingredients,
            "createdAt"   => $recipe["createdAt"],
            "updatedAt"   => $recipe["updatedAt"],
            "usersId"     => $recipe["usersId"]
        ];
    }

} catch (PDOException $e) {
    error_log("Recipe DB Error: " . $e->getMessage());
    $data = [];
}