<?php
require './includes/dbh.inc.php';
// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // <-- essentieel om $_SESSION te gebruiken
global $conn;

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

$userId = $_SESSION['userid'];

$stmt = $conn->prepare("SELECT usersId, usersName, usersEmail, usersUid FROM users WHERE usersId = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtRecipes = $conn->prepare("SELECT recipeId, title, createdAt FROM recipes WHERE usersId = ?");
$stmtRecipes->execute([$userId]);
$recipes = $stmtRecipes->fetchAll(PDO::FETCH_ASSOC);

include './header.php';
?>

<link rel="stylesheet" href="./styles/profile.css">

<div class="profile-container">

    <div class="profile-banner"></div>

    <div class="profile-card">

        <div class="profile-avatar">
            <img src="./images/user_placeholder.png" alt="User">
        </div>

        <h2 class="profile-name"><?= htmlspecialchars($user['usersName']) ?></h2>

        <button class="profile-manage-btn">Manage your account</button>

        <div class="profile-sections">

            <div class="profile-section">
                <h3>About</h3>

                <div class="profile-item">
                    <span class="icon">👤</span>
                    <p>Your name: <strong><?= htmlspecialchars($user['usersName']) ?></strong></p>
                </div>

                <div class="profile-item">
                    <span class="icon">📧</span>
                    <p>Email: <strong><?= htmlspecialchars($user['usersEmail']) ?></strong></p>
                </div>

                <div class="profile-item">
                    <span class="icon">🔑</span>
                    <p>Username: <strong><?= htmlspecialchars($user['usersUid']) ?></strong></p>
                </div>

                <a class="edit-btn" href="profile/profile_edit.php?id=<?= $user['usersId'] ?>">Gegevens bewerken</a>
            </div>

            <div class="profile-section">
                <h3>Your Recipes</h3>

                <?php if (!empty($recipes)): ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="profile-item recipe-card">
                            <span class="icon">🍽️</span>
                            <p>
                                <strong><?= htmlspecialchars($recipe['title']) ?></strong><br>
                                <small>Toegevoegd op: <?= $recipe['createdAt'] ?></small>
                            </p>

                            <a class="view-btn" href="./recipe/view_recipe.php?id=<?= $recipe['recipeId'] ?>">View</a>
                            <a class="view-btn" href="./recipe/change_recipe.php?id=<?= $recipe['recipeId'] ?>">Change</a>
                            <a class="view-btn" href="./recipe/delete_recipe.php?id=<?= $recipe['recipeId'] ?>">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Je hebt nog geen recepten toegevoegd.</p>
                <?php endif; ?>

                <a class="add-btn" href="./recipe/add_recipe.php">+ Nieuw recept toevoegen</a>
            </div>

        </div>

    </div>

</div>

<?php
include './footer.php';
?>
