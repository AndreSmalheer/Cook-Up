<?php

// Voor het tonen van erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken

// Maak connectie met de Database
require '../includes/dbh.inc.php';

                                    //Gegevens Ophalen//

// Controleren of die juist is verzonden | Alle gegevens uit de formulier halen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usersName      = trim($_POST['naam']);
    $usersEmail     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $usersUid       = trim($_POST['uid']);
    $token          = filter_input(INPUT_POST, 'token');
    $usersId        = trim($_POST['id']);

                                        // Validatie //

    // kijken over er een token is | Kijken of de token form gelijk is aan sessie token
    if (!isset($token) || $token !== $_SESSION['token']) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit();
    }

    // Fouten array aanmaken om de fouten er in te stopen
    $fouten = [];
    if (empty($usersName)) {
        $fouten['usersName'] = 'The users name is empty';
    }
    if (empty($usersEmail)) {
        $fouten['usersEmail'] = 'The users email is empty';
    }
    if (empty($usersUid)) {
        $fouten['usersUid'] = 'The usersname is empty';
    }

    // check if it is a valid email address
    if (!filter_var($usersEmail, FILTER_VALIDATE_EMAIL)) {
        $fouten['usersEmail'] = 'This is not a valid email address';
    }

    // Filteren //

    // De waardes veranderen naar html tags als die niet html eerst was.
    // strip_tags() zorgt ervoor dat iemand geen HTML of scripts kan meesturen, alleen gewone tekst.
    $usersName      = htmlspecialchars(strip_tags($usersName));
    $usersEmail     = htmlspecialchars(strip_tags($usersEmail));
    $usersUid       = htmlspecialchars(strip_tags($usersUid));
    $token          = htmlspecialchars(strip_tags($token));
    $usersId        = htmlspecialchars(strip_tags($usersId));


    // Bewerken Database //

    if (empty($fouten)) {
        global $conn;
        try {
            // De query om dingen te updaten
            $query = "UPDATE users
                      SET usersName = :usersName, usersEmail = :usersEmail, usersUid = :usersUid 
                      WHERE usersId = :usersId";

            $stmt = $conn->prepare($query); // Bereid de query voor

            // Voer de query uit || geeft waarde aan de placeholders
            $stmt->execute([
                'usersName'     => $usersName,
                'usersEmail'    => $usersEmail,
                'usersUid'      => $usersUid,
                'usersId'       => $usersId
            ]);

            //fetchAll() gebruik je om resultaten op te halen uit een SELECT query niet een INSERT query.
            //INSERT/Update query geeft geen rijen terug, dus $stmt->fetchAll() zou hier een lege array opleveren.
            $resultaten = [[
                'usersName'     => $usersName,
                'usersEmail'    => $usersEmail,
                'usersUid'      => $usersUid,
                'usersId'       => $usersId
            ]];

            // Als alles goed is ga terug naar de profilepage
            header("location: ../profile.php");
            exit();

        } catch (PDOException $e) {
            echo "Fout bij het toevoegen: " . $e->getMessage();
            exit;
        }
    }

    if (!empty($fouten)) {
        //Met include → kan je gewoon $fouten meegeven en hoef je geen $_SESSION.
        //Met redirect → moet je $_SESSION of GET-parameters gebruiken om de fouten te sturen.
        $_SESSION['fouten'] = $fouten; // Stuur de fouten door
        header("location: ./profile_edit.php?id=" . $usersId);
        exit();
    }
}