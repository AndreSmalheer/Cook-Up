<?php
global $recipe, $category, $tags, $ingredients, $steps;
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/view_recipe.css">
    <title>Recept bekijken</title>
</head>
<body>

<div class="recipe-container">

    <h1 class="title"><?= htmlspecialchars($recipe['title']) ?></h1>

    <div class="info-box">
        <p><strong>Beschrijving:</strong><br><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
        <p><strong>Categorie:</strong> <?= htmlspecialchars($category ?? "Onbekend") ?></p>
        <p><strong>Auteur (User ID):</strong> <?= $recipe['usersId'] ?></p>
        <p><strong>Aangemaakt op:</strong> <?= $recipe['createdAt'] ?></p>
        <p><strong>Laatst bijgewerkt:</strong> <?= $recipe['updatedAt'] ?></p>
    </div>

    <h2>Tags</h2>
    <div class="tags-box">
        <?php if (!empty($tags)) { ?>
            <?php foreach ($tags as $t) { ?>
                <span class="tag"><?= htmlspecialchars($t) ?></span>
            <?php } ?>
        <?php } else { ?>
            <p>Geen tags gevonden.</p>
        <?php } ?>
    </div>

    <h2>Ingrediënten</h2>
    <ul class="ingredients-list">
        <?php foreach ($ingredients as $ing) { ?>
            <li>
                <?= htmlspecialchars($ing['name']) ?>
                <?php if (!empty($ing['quantity'])) { ?>
                    - <em><?= htmlspecialchars($ing['quantity']) ?></em>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>

    <h2>Bereidingsstappen</h2>
    <ol class="steps-list">
        <?php foreach ($steps as $s) { ?>
            <li><?= htmlspecialchars($s['instruction']) ?></li>
        <?php } ?>
    </ol>

    <div class="navLinks">
        <a href="../profile.php">← Terug naar Profile</a>
    </div>

</div>

</body>
</html>
