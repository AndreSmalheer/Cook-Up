<?php
/** @noinspection ALL */

// Voor het tonen van errors tijdens dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

                            /* === Validatie functies === */
function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) {
    return empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat);
}

function invalidUid($username) {
    return !preg_match("/^[a-zA-Z0-9]*$/", $username);
}

function invalidEmail($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function pwdMatch($pwd, $pwdRepeat) {
    return $pwd !== $pwdRepeat;
}

function emptyInputLogin($username, $pwd) {
    return empty($username) || empty($pwd);
}

                            /* === Database functies === */

/**
 * Controleer of een gebruikersnaam of e-mail al bestaat.
 * Retouneert de hele rij assoc als gevonden, anders false.
 */
function uidExists($conn, $username, $email) {
    try {
        $sql = "SELECT * FROM users WHERE usersUid = :uid OR usersEmail = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':uid' => $username, ':email' => $email]);
        $row = $stmt->fetch();
        return $row ? $row : false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Maak een nieuwe gebruiker aan de role wordt automatisch user meegegeven.
 */
function createUser($conn, $name, $email, $username, $pwd) {
    try {
        $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, role)
                VALUES (:name, :email, :uid, :pwd, :role)";
        $stmt = $conn->prepare($sql);
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':uid' => $username,
            ':pwd' => $hashedPwd,
            ':role' => 'user'
        ]);
        header('location: ../signup.php?error=none');
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header('location: ../signup.php?error=stmtfailed');
        exit();
    }
}

/**
 * Login een gebruiker.
 */
function loginUser($conn, $username, $pwd) {
    // kan email of gebruikersnaam zijn
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false) {
        header('location: ../login.php?error=wrongloginusername');
        exit();
    }

    $pwdHashed = $uidExists['usersPwd'];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header('location: ../login.php?error=wrongloginpassword');
        exit();
    } else {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['userid'] = $uidExists['usersId'];
        $_SESSION['useruid'] = $uidExists['usersUid'];
        $_SESSION['role'] = isset($uidExists['role']) ? $uidExists['role'] : 'user';
        header('location: ../index.php');
        exit();
    }
}