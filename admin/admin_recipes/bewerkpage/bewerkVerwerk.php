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
require '../../../includes/dbh.inc.php';

                                        //Gegevens Ophalen//

// Controleren of die juist is verzonden | Alle gegevens uit de formulier halen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title              = trim($_POST['title']);
    $description        = trim($_POST['description']);
    $token              = filter_input(INPUT_POST, 'token');
    $recipeId           = trim($_POST['id']);

                                        // Validatie //

    // kijken over er een token is | Kijken of de token form gelijk is aan sessie token
    if (!isset($token) || $token !== $_SESSION['token']) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit();
    }

    // Fouten array aanmaken om de fouten er in te stopen
    $fouten = [];
    if (empty($title)) {
        $fouten['title'] = 'The title is empty';
    }
    if (empty($description)) {
        $fouten['description'] = 'The description is empty';
    }

                                        // Filteren //

    // De waardes veranderen naar html tags als die niet html eerst was.
    // strip_tags() zorgt ervoor dat iemand geen HTML of scripts kan meesturen, alleen gewone tekst.
    $title           = htmlspecialchars(strip_tags($title));
    $description     = htmlspecialchars(strip_tags($description));
    $token           = htmlspecialchars(strip_tags($token));
    $recipeId        = htmlspecialchars(strip_tags($recipeId));


                                    // Bewerken Database //

    if (empty($fouten)) {
        global $conn;
        try {
            // De query om dingen te updaten
            $query = "UPDATE recipes
                      SET title = :title, description = :description
                      WHERE recipeId = :recipeId";

            $stmt = $conn->prepare($query); // Bereid de query voor

            // Voer de query uit || geeft waarde aan de placeholders
            $stmt->execute([
                'title'         => $title,
                'description'   => $description,
                'recipeId'      => $recipeId
            ]);

            //fetchAll() gebruik je om resultaten op te halen uit een SELECT query niet een INSERT query.
            //INSERT/Update query geeft geen rijen terug, dus $stmt->fetchAll() zou hier een lege array opleveren.
            $resultaten = [[
                'title'         => $title,
                'description'   => $description,
                'recipeId'      => $recipeId
            ]];

            // Als alles goed is ga terug naar de adminpage
            header("location: ../../../admin/admin.php");
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
        header("location: ./bewerk.php?id=" . $recipeId);
        exit();
    }
}