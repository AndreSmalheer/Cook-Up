<?php
include_once './header.php';
require './includes/dbh.inc.php';
global $conn;

// Start alleen een sessie als er nog geen actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // essentieel om $_SESSION te gebruiken

$category_id = '';
if ( isset($_GET['catogory_id']) ) {
    $category_id = $_GET['catogory_id'];
};
$catogory_name = '';
if ( isset($_GET['catogory_name']) ) {
    $catogory_name = $_GET['catogory_name'];
};
$filterTag = '';
if ( isset($_GET['tag']) ) {
    $filterTag = $_GET['tag'];
};

$item_to_add = [];

// tags filteren
if ($filterTag) {
    $sql = "SELECT r.recipeId, r.title, r.description, r.categoryId, r.imagePath, r.createdAt
            FROM recipes r
            JOIN recipe_tags rt ON rt.recipeId = r.recipeId
            JOIN tags t ON t.tagId = rt.tagId
            WHERE LOWER(t.name) = LOWER(:tag)
            ORDER BY r.createdAt DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':tag' => $filterTag]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Als we op categorie filteren
} elseif ($category_id) {
    $sql = "SELECT recipeId, title, description, categoryId, imagePath, createdAt
            FROM recipes
            WHERE categoryId = :cat
            ORDER BY createdAt DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':cat' => $category_id]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// anders: alle recipes
} else {
    $sql = "SELECT recipeId, title, description, categoryId, imagePath, createdAt
            FROM recipes
            ORDER BY createdAt DESC
            LIMIT 50";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Bouw $item_to_add naar hetzelfde format als oude $data
foreach ($recipes as $r) {
    $recipeId = $r['recipeId'];

    // ingrediënten
    $stmtIng = $conn->prepare("SELECT name, quantity FROM ingredients WHERE recipeId = ? ORDER BY ingredientId ASC");
    $stmtIng->execute([$recipeId]);
    $ingRows = $stmtIng->fetchAll(PDO::FETCH_ASSOC);
    $ingredients = [];
    foreach ($ingRows as $i) $ingredients[] = ($i['quantity']? $i['quantity'].' ':'') . $i['name'];

    // stappen
    $stmtSteps = $conn->prepare("SELECT stepNumber, instruction FROM steps WHERE recipeId = ? ORDER BY stepNumber ASC");
    $stmtSteps->execute([$recipeId]);
    $stepsRows = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);
    $steps = [];
    foreach ($stepsRows as $s) $steps[] = ['stepNumber'=>$s['stepNumber'],'instructions'=>$s['instruction']];

    // tags
    $stmtTags = $conn->prepare("SELECT t.name FROM tags t JOIN recipe_tags rt ON rt.tagId = t.tagId WHERE rt.recipeId = ?");
    $stmtTags->execute([$recipeId]);
    $tagRows = $stmtTags->fetchAll(PDO::FETCH_COLUMN);
    $tags = array_map('strtolower', $tagRows);

    $img = $r['imagePath'] ? $r['imagePath'] : 'recepten/placeholder.jpg';

    $item_to_add[] = [
            'id' => (string)$recipeId,
            'category_id' => (string)$r['categoryId'],
            'title' => $r['title'],
            'img' => $img,
            'description' => $r['description'],
            'tags' => $tags,
            'steps' => $steps,
            'ingredients' => $ingredients
    ];
}

// Build $all_tags from items currently loaded
$all_tags = [];
foreach ($item_to_add as $item) {
    if (!empty($item['tags'])) {
        foreach ($item['tags'] as $tag) $all_tags[$tag] = true;
    }
}
$all_tags = array_keys($all_tags);
sort($all_tags);
?>

    <link rel="stylesheet" href="./styles/pages/overview_page/overview_page.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 700,  
        once: true      
    });
</script>


<div id="overview_parrent">

<div id="side_bar">
    <h1><?= $catogory_name ?></h1>

    <div class="search" id="overview_search">
        <button class="search-btn"><img src="images/magnifying-glass.png"></button>
        <input type="text" id="overview_search_input" class="search-input" placeholder="Search">
    </div>

    <div class="category-container">
        <button class="category-btn" id="cat_button">Categories ▼</button>
        <ul class="category-list" id="list">
        <?php foreach ($all_tags as $tag): ?>
            <li onclick="showCatogory('<?= htmlspecialchars($tag) ?>')">
                <?= htmlspecialchars($tag) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>  
</div>


<div id="recepy_wrapper">
<?php foreach ($item_to_add as $index => $item) : ?>
    <div class="recepy active" 
         data-id="<?= htmlspecialchars($item['id']) ?>" 
         data-aos="fade-up"
         data-aos-delay="<?= $index * 100 ?>">

        <div class="recepy-header">
            <img src="<?= htmlspecialchars($item["img"]) ?>" alt="<?= htmlspecialchars($item["title"]) ?>">
        </div>

        <h1 class="recepy-title"><?= htmlspecialchars($item['title']) ?></h1>

        <?php if (!empty($item["tags"])): ?>
            <div class="recepy-tags">
                <?php foreach ($item["tags"] as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="recepy-description"><?= htmlspecialchars($item['description']) ?></p>
    </div>
<?php endforeach; ?>
</div>


<script type="module" defer>
    import { search } from "./js/search.js"
    document.getElementById("header").classList.add("shrink")

    const overview_search = document.getElementById("overview_search_input") 

    console.log(overview_search)


    overview_search.addEventListener("input", function() {
        const searchText = overview_search.value;
        search(document.getElementById("recepy_wrapper"), ".recepy.active", searchText, ".recepy-title");
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const category_btn = document.getElementById("cat_button");

    category_btn.addEventListener("click", () => {
        document.querySelector(".category-container").classList.toggle("active");
    });
});
</script>

<script>
document.querySelectorAll('.recepy').forEach(recepy => {
    recepy.addEventListener('click', () => {
        id = recepy.dataset.id
        url = `shorts.php?recept_id=${id}`
         window.location.href = url;
    });
});


function showCatogory(category) {
    const recepy_wrapper = document.getElementById("recepy_wrapper");
    const recepies = recepy_wrapper.querySelectorAll(".recepy");

    recepies.forEach(recepy => {
        const tags = recepy.querySelectorAll(".tag");
        let shouldShow = false;

        tags.forEach(tag => {
            let tag_text = tag.innerHTML.toLowerCase();
            let category_lower = category.toLowerCase();

            if (tag_text === category_lower) {
                shouldShow = true;
            }
        });

        if (shouldShow) {
            const parent = recepy.parentNode;
            const next = recepy.nextSibling;
        
            parent.removeChild(recepy);
        
            recepy.classList.remove("aos-animate");
            recepy.style.display = "block";
            recepy.classList.add("active");
        
            if (next) parent.insertBefore(recepy, next);
            else parent.appendChild(recepy);
        } else {
            recepy.classList.remove("active");
            recepy.style.display = "none";
        }
    });
    AOS.refreshHard();
}
</script>


<?php
include 'footer.php';
?>