<?php
global $resultaat;
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruiker verwijderen | Admin</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<section class="delete-container">
    <h1>Gebruiker verwijderen</h1>

    <?php if (!empty($resultaat)) { ?>
        <p class="warning-text">
            Weet je zeker dat je de gebruiker <strong><?= htmlspecialchars($resultaat['usersName']) ?></strong> wilt verwijderen?
            Deze actie kan <span class="emphasis">niet ongedaan</span> worden gemaakt.
        </p>

        <div class="user-info">
            <ul>
                <li><strong>ID:</strong> <?= htmlspecialchars($resultaat['usersId']) ?></li>
                <li><strong>Naam:</strong> <?= htmlspecialchars($resultaat['usersName']) ?></li>
                <li><strong>Email:</strong> <?= htmlspecialchars($resultaat['usersEmail']) ?></li>
                <li><strong>Gebruikersnaam:</strong> <?= htmlspecialchars($resultaat['usersUid']) ?></li>
                <li><strong>Rol:</strong> <?= htmlspecialchars($resultaat['role']) ?></li>
            </ul>
        </div>

        <form method="post" action="verwijderen.php?id=<?= htmlspecialchars($resultaat['usersId']) ?>" class="delete-form">
            <div class="button-group">
                <button type="submit" class="btn btn-danger">Verwijderen</button>
                <a href="../../admin.php" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>

    <?php } else { ?>
        <div class="no-result">
            <p>Geen gebruiker gevonden met deze ID.</p>
            <a href="../../admin.php" class="btn btn-secondary">Terug naar de Adminpagina</a>
        </div>
    <?php } ?>
</section>

</body>
</html>
