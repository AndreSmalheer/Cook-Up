<?php

// Connectie maken met de database
require '../../../includes/dbh.inc.php';

// Kijken of de gebruiker een admin is
require '../../../includes/auth_admin.inc.php';

$id = $_GET['id']; // Haal de id op uit de url

function Find_subject_by_id($id) {
    global $conn;
    try {
        $query = "SELECT usersId, usersName, usersEmail, usersUid, role FROM users
                  WHERE usersId = :id";
        $stmt = $conn->prepare($query); // bereid de query voor
        $stmt->bindParam(':id', $id); // Bind de id aan de placeholder
        $stmt->execute(); // voert de query uit

        // pakt de resultaat uit de sql en zet het in een array
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit("fout bij het ophalen van de id" . $e->getMessage());
    }
}

$resultaat = Find_subject_by_id($id);
include './bewerk_view.php';