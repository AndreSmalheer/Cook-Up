<?php

// Connectie maken met de database
require '../includes/dbh.inc.php';


// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

$id = $_GET['id']; // Haal de id uit de url

function Find_subject_by_id($id) {
    global $conn;
    try {
        $query = "SELECT * FROM recipes
                  WHERE recipeId = :id";
        $stmt = $conn->prepare($query); // bereid de query voor
        $stmt->bindParam(':id', $id); // Bind de id aan de placeholder
        $stmt->execute(); // voert de query uit

        // pakt de resultaat uit de sql en zet het in een array
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit("fout bij het ophalen van de id" . $e->getMessage());
    }
}

function Delete_subject_by_id($id) {
    global $conn;
    try {
        $query = "DELETE FROM recipes WHERE recipeId = :id"; // delete de record van de id
        $stmt = $conn->prepare($query); // bereid de query voor
        $stmt->bindParam(':id', $id); // bind de placeholder met de id
        $stmt->execute();

        //Na het verwijderen terug sturen naar de profilepage
        header("location: ../profile.php");
        exit();
    } catch (PDOException $e) {
        exit("Fout bij het verwijderen" . $e->getMessage());
    }
}

if (empty($_GET['id'])) {
    header("location: .../profile.php");
    exit();
}

// Als er op de bevestigingsknop id gedrukt verwijder dan het record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Delete_subject_by_id($id);
}


// Laat de gegevens zien van de record
$resultaat = Find_subject_by_id($id);
include './delete_inc_recipe.php';