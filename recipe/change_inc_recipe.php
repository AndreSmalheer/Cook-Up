<?php

// Maak de resultaat array globaal
global $resultaat;
global $conn;

// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken

// Maak een token aan als die er niet is
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
$token = $_SESSION['token'];

// Haal de fouten op uit de verwerk
$fouten = [];
if (isset($_SESSION['fouten'])) {
    $fouten = $_SESSION['fouten'];
    unset($_SESSION['fouten']); // zodat ze niet staan bij reload
}

// categorien ophalen
$stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// tags ophalen
$stmt = $conn->prepare("SELECT * FROM tags ORDER BY name ASC");
$stmt->execute();
$allTags = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../styles/change_recipe.css">
    <title>Bewerking Recepten</title>
</head>
<body>
<h1>Recept Bewerken</h1>

<form method="post" action="change_proces.php">

    <label for="title">Titel:</label>
    <input type="text" name="title" id="title"
           value="<?= htmlspecialchars($resultaat['recipe']['title']) ?>">

    <label for="description">Beschrijving:</label>
    <textarea name="description" id="description" rows="3"><?=
        htmlspecialchars($resultaat['recipe']['description'])
        ?></textarea>

    <label for="category">Categorie:</label>
    <select name="categoryId" id="category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['categoryId'] ?>"
                <?= ($cat['categoryId'] == $resultaat['recipe']['categoryId']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tags:</label>
    <div class="tags-edit">
        <?php foreach ($allTags as $tag): ?>
            <label>
                <input type="checkbox" name="tags[]"
                       value="<?= $tag['tagId'] ?>"
                    <?= in_array($tag['tagId'], $resultaat['tags']) ? 'checked' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <h2>Ingrediënten</h2>
    <div id="ingredients-wrapper">
        <?php foreach ($resultaat['ingredients'] as $i => $ing): ?>
            <div class="ingredient-row">
                <input type="text" name="ingredients[<?= $i ?>][name]"
                       value="<?= htmlspecialchars($ing['name']) ?>" required>
                <input type="text" name="ingredients[<?= $i ?>][quantity]"
                       value="<?= htmlspecialchars($ing['quantity']) ?>">
                <input type="hidden" name="ingredients[<?= $i ?>][id]"
                       value="<?= $ing['ingredientId'] ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="add-ingredient-btn">+ Ingrediënt toevoegen</button>

    <h2>Bereidingsstappen</h2>
    <div id="steps-wrapper">
        <?php foreach ($resultaat['steps'] as $s => $step): ?>
            <div class="step-row">
                <textarea name="steps[<?= $s ?>][instruction]" required><?=
                    htmlspecialchars($step['instruction'])
                    ?></textarea>
                <input type="hidden" name="steps[<?= $s ?>][id]"
                       value="<?= $step['stepId'] ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="add-step-btn">+ Stap toevoegen</button>

    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="hidden" name="id" value="<?= $resultaat['recipe']['recipeId'] ?>">

    <button type="submit" class="btn-save">Opslaan</button>

    <div class="navLinks">
        <a href="../profile.php">Terug</a>
    </div>

</form>

<script defer src="../js/change_recipe.js"></script>

</body>
</html>