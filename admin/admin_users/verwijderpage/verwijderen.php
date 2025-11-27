<?php

// Connectie maken met de database
require '../../../includes/dbh.inc.php';

// Kijken of de gebruiker een admin is
require '../../../includes/auth_admin.inc.php';

$usersId = $_GET['id']; // Haal de id uit de url

function Find_subject_by_id($usersID) {
    global $conn;
    try {
        $query = "SELECT usersId, usersName, usersEmail, usersUid, role FROM users
                  WHERE usersId = :id";
        $stmt = $conn->prepare($query); // bereid de query voor
        $stmt->bindParam(':id', $usersID); // Bind de id aan de placeholder
        $stmt->execute(); // voert de query uit

        // pakt de resultaat uit de sql en zet het in een array
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit("fout bij het ophalen van de id" . $e->getMessage());
    }
}

function Delete_subject_by_id($usersId) {
    global $conn;
    try {
        $query = "DELETE FROM users WHERE usersId = :id"; // delete de record van de id
        $stmt = $conn->prepare($query); // bereid de query voor
        $stmt->bindParam(':id', $usersId); // bind de placeholder met de id
        $stmt->execute();

        //Na het verwijderen terug sturen naar de adminpage
        header("location: ../../../admin/admin.php");
        exit();
    } catch (PDOException $e) {
        exit("Fout bij het verwijderen" . $e->getMessage());
    }
}

if (empty($_GET['id'])) {
    header("location: ../../admin/admin.php");
    exit();
}

// Als er op de bevestigingsknop id gedrukt verwijder dan het record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Delete_subject_by_id($usersId);
}


// Laat de gegevens zien van de record
$resultaat = Find_subject_by_id($usersId);
include './verwijderen_view.php';