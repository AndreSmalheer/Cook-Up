<?php
// Voor het tonen van errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $conn;

if (isset($_POST['submit'])) {

    $username = $_POST['uid'];
    $pwd = $_POST['pwd'];

    require_once './dbh.inc.php';
    require_once './functions.inc.php';

    if (emptyInputLogin($username, $pwd) !== false) {
        header('location: ../login.php?error=emptyinput');
        exit();
    }

    loginUser($conn, $username, $pwd);

} else {
    header('location: ../login.php');
    exit();
}
