<?php
require '../includes/dbh.inc.php';
// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken
global $conn;

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

// categorie ophalen
$stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// tags ophalen
$stmt2 = $conn->prepare("SELECT * FROM tags ORDER BY name ASC");
$stmt2->execute();
$tags = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../styles/add_recipe.css">
    <title>Recepten toevoegen</title>
</head>
<body>

<div class="add-recipe-wrapper">

    <h1>Nieuw Recept Toevoegen</h1>

    <form action="add_recipe_inc.php" method="POST" class="recipe-form">

        <label for="title">Titel van recept</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Korte beschrijving</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <label for="category">Categorie</label>
        <select name="categoryId" id="category" required>
            <option value="">Kies een categorie</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['categoryId'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Tags</label>
        <div class="tag-container">
            <?php foreach ($tags as $tag): ?>
                <label class="tag-item">
                    <input type="checkbox" name="tags[]" value="<?= $tag['tagId'] ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label for="imagePath">Image URL</label>
        <input type="url" name="imagePath" id="imagePath" placeholder="https://example.com/image.jpg" required>

        <h2>Ingrediënten</h2>
        <div id="ingredients-wrapper"></div>
        <button type="button" id="add-ingredient-btn" class="btn-add">+ Ingrediënt toevoegen</button>

        <h2>Bereidingsstappen</h2>
        <div id="steps-wrapper"></div>
        <button type="button" id="add-step-btn" class="btn-add">+ Stap toevoegen</button>

        <button type="submit" class="btn-submit">Recept opslaan</button>

        <div class="navLinks">
            <a href="../profile.php">Terug naar de profilepage</a>
        </div>

    </form>

</div>

<script src="../js/add_recipe.js"></script>

</body>
</html>
