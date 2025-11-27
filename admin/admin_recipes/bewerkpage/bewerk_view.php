<?php

// Maak de resultaat array globaal
global $resultaat;

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

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>Bewerking Recepten</title>
</head>
<body>
<h1>Bewerken van recipe gegevens</h1>

<div></div>

<form method="post" action="bewerkVerwerk.php">

    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="<?= $resultaat['title'] ?>">
    <?php if (isset($fouten['title'])) { ?>
        <p class="error"><strong> <?= $fouten['title'] ?></strong></p>
    <?php } ?>

    <label for="description">Description:</label>
    <input type="text" name="description" id="description" value="<?= $resultaat['description'] ?>">
    <?php if (isset($fouten['description'])) { ?>
        <p class="error"><strong> <?= $fouten['description'] ?></strong></p>
    <?php } ?>


    <input type="hidden" name="token" id="token" value="<?= $token ?>">

    <input type="hidden" name="id" id="id" value="<?= $resultaat['recipeId'] ?>">

    <button type="submit">Bewerken</button>

    <div class="navLinks">
        <a href="../../admin.php">Terug naar de Adminpage</a>
    </div>

</form>

</body>
</html>
