<?php

// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken

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
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../styles/profile_edit.css">
    <title>Bewerking Users</title>
</head>
<body>

<h1>Bewerken van user gegevens</h1>

<div></div>

<form method="post" action="profile_edit_verwerk.php">

    <label for="naam">Naam:</label>
    <input type="text" name="naam" id="naam" value="<?= $resultaat['usersName'] ?>">
    <?php if (isset($fouten['usersName'])) { ?>
        <p class="error"><strong> <?= $fouten['usersName'] ?></strong></p>
    <?php } ?>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= $resultaat['usersEmail']?>">
    <?php if (isset($fouten['usersEmail'])) { ?>
        <p class="error"><strong> <?= $fouten['usersEmail'] ?></strong></p>
    <?php } ?>

    <label for="uid">Gebruikersnaam:</label>
    <input type="text" name="uid" id="uid" value="<?= $resultaat['usersUid'] ?>">
    <?php if (isset($fouten['usersUid'])) { ?>
        <p class="error"><strong> <?= $fouten['usersUid'] ?></strong></p>
    <?php } ?>


    <input type="hidden" name="token" id="token" value="<?= $token ?>">

    <input type="hidden" name="id" id="id" value="<?= $resultaat['usersId'] ?>">

    <button type="submit">Bewerken</button>

    <div class="navLinks">
        <a href="../profile.php?id=<?= $resultaat['usersId'] ?>">Terug naar de profilepage</a>
    </div>

</form>

</body>
</html>