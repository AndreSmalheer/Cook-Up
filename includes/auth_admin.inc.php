<?php

//Kijken of de gebruiker die de site bekijkt een gebruiker is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['useruid'])) {

    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] !== 'admin') {
            header('location: ../login.php?error=wrongrole');
            exit();
        }
    } else {
        header('location: ../index.php?error=unknownrole');
        exit();
    }

} else {
    header('location: ../index.php?error=nouser');
    exit();
}
