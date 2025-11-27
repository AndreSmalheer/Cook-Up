<?php
global $resultaat;
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe verwijderen</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../styles/delete_recipe.css">
</head>
<body>

<section class="delete-container">
    <h1>Recipe verwijderen</h1>

    <?php if (!empty($resultaat)) { ?>
        <p class="warning-text">
            Weet je zeker dat je de recipe <strong><?= htmlspecialchars($resultaat['title']) ?></strong> wilt verwijderen?
            Deze actie kan <span class="emphasis">niet ongedaan</span> worden gemaakt.
        </p>

        <div class="user-info">
            <ul>
                <li><strong>RecipeId:</strong> <?= htmlspecialchars($resultaat['recipeId']) ?></li>
                <li><strong>Title:</strong> <?= htmlspecialchars($resultaat['title']) ?></li>
                <li><strong>Description:</strong> <?= htmlspecialchars($resultaat['description']) ?></li>
                <li><strong>CategoryId:</strong> <?= htmlspecialchars($resultaat['categoryId']) ?></li>
                <li><strong>CreatedAt:</strong> <?= htmlspecialchars($resultaat['createdAt']) ?></li>
                <li><strong>UpdatedAt:</strong> <?= htmlspecialchars($resultaat['updatedAt']) ?></li>
            </ul>
        </div>

        <form method="post" action="delete_recipe.php?id=<?= htmlspecialchars($resultaat['recipeId']) ?>" class="delete-form">
            <div class="button-group">
                <button type="submit" class="btn btn-danger">Verwijderen</button>
                <a href="../profile.php" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>

    <?php } else { ?>
        <div class="no-result">
            <p>Geen recipe gevonden met deze ID.</p>
            <a href="../profile.php" class="btn btn-secondary">Terug naar de Profile page</a>
        </div>
    <?php } ?>
</section>

</body>
</html>