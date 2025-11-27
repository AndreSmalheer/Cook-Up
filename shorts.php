<?php
include './includes/dbh.inc.php';
include './header.php';
global $conn;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$category_id = '';
if ( isset($_GET['catogory_id']) ) {
    $category_id = $_GET['catogory_id'];
};

$recept_id   = '';
if ( isset($_GET['recept_id']) ) {
    $recept_id = $_GET['recept_id'];
};

// Hier alle recepten opslaan ook de random recepten
$item_to_add = [];

// Functie haal 1 recept op
function getRecipeById($conn, $id) {
    $sql = "SELECT recipeId, title, description, categoryId, imagePath 
            FROM recipes 
            WHERE recipeId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie haal alle ingredients van recept
function getIngredients($conn, $recipeId) {
    $sql = "SELECT name, quantity 
            FROM ingredients 
            WHERE recipeId = ?
            ORDER BY ingredientId ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$recipeId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $list = [];

    foreach ($rows as $i) {
        if (!empty($i["quantity"])) {
            $list[] = $i["quantity"] . " " . $i["name"];
        } else {
            $list[] = $i["name"];
        }
    }

    return $list;
}

// Functie haal steps op van recept
function getSteps($conn, $recipeId) {
    $sql = "SELECT stepNumber, instruction
            FROM steps
            WHERE recipeId = ?
            ORDER BY stepNumber ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$recipeId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $steps = [];

    foreach ($rows as $s) {
        $steps[] = [
                "stepNumber"   => $s["stepNumber"],
                "instructions" => $s["instruction"]
        ];
    }

    return $steps;
}

// Gekozen recept ophalen en toevoegen als er een id
if ($recept_id) {

    $r = getRecipeById($conn, $recept_id);

    if ($r) {
        $item_to_add[] = [
                "id"          => (int)$r["recipeId"],
                "category_id" => (int)$r["categoryId"],
                "title"       => $r["title"],
                "img"         => $r["imagePath"] ?: "recepten/placeholder.jpg",
                "description" => $r["description"],
                "ingredients" => getIngredients($conn, $r["recipeId"]),
                "steps"       => getSteps($conn, $r["recipeId"]),
        ];
    }
}

// Random extra recepten ophalen
$sql = "SELECT recipeId, title, description, categoryId, imagePath FROM recipes ";
$params = [];

if ($recept_id) {
    $sql .= "WHERE recipeId != ? ";
    $params[] = $recept_id;
}

$sql .= "ORDER BY RANDOM() LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$randomRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// voeg alle random recepten toe
foreach ($randomRecipes as $r) {

    $rid = (int)$r["recipeId"];

    $item_to_add[] = [
            "id"          => $rid,
            "category_id" => (int)$r["categoryId"],
            "title"       => $r["title"],
            "img"         => $r["imagePath"] ?: "recepten/placeholder.jpg",
            "description" => $r["description"],
            "ingredients" => getIngredients($conn, $rid),
            "steps"       => getSteps($conn, $rid)
    ];
}
?>
<script>
    document.getElementById("header").classList.add("shrink");
</script>
<link rel="stylesheet" href="styles/shorts.css">

<div id="parrent_short_container">

    <div id="short_container">
        <?php $first = true; ?>
        <?php foreach ($item_to_add as $item): ?>

            <div class="short <?= $first ? 'active' : '' ?>">
                <h1><?= htmlspecialchars($item["title"]) ?></h1>
                <img src="<?= htmlspecialchars($item["img"]) ?>" alt="">
            </div>

            <?php $first = false; ?>
        <?php endforeach; ?>
    </div>

    <!-- info box rechts -->
    <div id="recepy">
        <?php if (!empty($item_to_add)): ?>
            <?php $firstRecipe = $item_to_add[0]; ?>

            <h2><?= htmlspecialchars($firstRecipe["title"]) ?></h2>
            <p><?= htmlspecialchars($firstRecipe["description"]) ?></p>

            <h3>Ingredients</h3>
            <ul>
                <?php foreach ($firstRecipe["ingredients"] as $ing): ?>
                    <li><?= htmlspecialchars($ing) ?></li>
                <?php endforeach; ?>
            </ul>

            <?php if (!empty($firstRecipe["steps"])): ?>
                <h3>Steps</h3>
                <ol>
                    <?php foreach ($firstRecipe["steps"] as $step): ?>
                        <li>
                            <strong>Step <?= $step["stepNumber"] ?>:</strong>
                            <?= htmlspecialchars($step["instructions"]) ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

        <?php endif; ?>
    </div>


    <!-- Buttons -->
    <div id="button_container">
        <div id="favroute" class="short_button" onclick="heart()">
            <img src="images/heart_unfilled.png" alt="">
        </div>

        <div id="info" class="short_button" onclick="toggle_recepy_window()">
            <img src="images/recepy.png" alt="">
        </div>
    </div>
</div>


<!-- recepten naar js sturen -->
<script>
    const ITEMS = <?= json_encode($item_to_add) ?>;
</script>

<script src="js/shorts.js"></script>
