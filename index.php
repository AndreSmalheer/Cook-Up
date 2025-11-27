<?php

require './includes/dbh.inc.php';
include './header.php';
include './includes/recipes_db.inc.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
global $data;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">


<div id="banner-1">
    <img src="./images/4e66cb90-fd12-4254-95ef-ad76a4dece73.jpg">
    <h1>
     From <span class="highlight">Cravings</span> to <span class="highlight2">Creations</span>
    </h1>
</div>

<div class="category-container">
    <div class="title-search-container" data-aos="fade-down">
        <h1>Category</h1>
        <div class="search" id="catogory_search">
            <button class="search-btn"><img src="images/magnifying-glass.png"></button>
            <input type="text" id="catogory_search_input" class="search-input" placeholder="Search..." />
        </div>
    </div>

    <div class="category-feed" id="catogory_items">
        <div class="category-item" onclick="goToUrl('overview.php?catogory_id=1&catogory_name=Breakfast')" data-aos="fade-up" data-aos-delay="0">
            <div class="category-header">
                <img src="images/catogories/breakfast.jpg" alt="Category Icon">
            </div>
            <h2 class="category-title">Breakfast</h2>
            <p class="category-description">
                Breakfast is the first meal of the day, providing energy, boosting focus, and preparing the body and mind for daily activities.
            </p>
        </div>

        <div class="category-item" onclick="goToUrl('overview.php?catogory_id=2&catogory_name=Lunch')" data-aos="fade-up" data-aos-delay="100">
            <div class="category-header">
                <img src="images/catogories/lunch.jpg" alt="Category Icon">
            </div>
            <h2 class="category-title">Lunch</h2>
            <p class="category-description">
                Lunch is a midday meal that refuels the body, maintains energy levels, and supports focus and productivity for the rest of the day.
            </p>
        </div>

        <div class="category-item" onclick="goToUrl('overview.php?catogory_id=3&catogory_name=Dinner')" data-aos="fade-up" data-aos-delay="200">
            <div class="category-header">
                <img src="images/catogories/dinner.jpg" alt="Category Icon">
            </div>
            <h2 class="category-title">Dinner</h2>
            <p class="category-description">
                Dinner is the evening meal that nourishes the body, promotes relaxation, and provides an opportunity to connect and unwind after the day.
            </p>
        </div>

        <div class="category-item" onclick="goToUrl('overview.php?catogory_id=4&catogory_name=Snacks')" data-aos="fade-up" data-aos-delay="300">
            <div class="category-header">
                <img src="images/catogories/snacks.jpg" alt="Category Icon">
            </div>
            <h2 class="category-title">Snacks</h2>
            <p class="category-description">
                Snacks are small, quick bites that satisfy hunger, boost energy, and keep you focused between main meals.
            </p>
        </div>
    </div>
</div>

<div id="banner-2" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
    <?php
    if (isset($_SESSION['useruid'])) {
        ?>
        <img src="images/duties-of-a-chef.jpg">
        <h1 data-aos="fade-up" style="bottom: -10% !important;" data-aos-duration="1000" data-aos-delay="300">
            Get<br> <span class="highlight">Cooking!</span>
        </h1>
        <button data-aos="fade-up" onclick="goToUrl('shorts.php')" data-aos-duration="1000" data-aos-delay="0">
            Explore Recipes <i class="fas fa-utensils"></i>
        </button>
        <?php
    } else {
        ?>
        <img src="images/duties-of-a-chef.jpg">
        <h1 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            Login om <span class="highlight">recepten<br></span> toe te voegen
        </h1>
        <button data-aos="fade-up" onclick="goToUrl('login.php')" data-aos-duration="1000" data-aos-delay="0">
            Login <i class="fas fa-right-to-bracket"></i>
        </button>
        <?php
    }
    ?>
</div>

<div class="category-container">
    <div class="title-search-container" data-aos="fade-down">
        <h1>Trending</h1>
        <div class="search">
            <button class="search-btn"><img src="images/magnifying-glass.png"></button>
            <input type="text" id="trending_search_input" class="search-input" placeholder="Search..." />
        </div>
    </div>

    <div class="category-feed" id="trending_items">
        <?php 
        foreach ($data as $item) {
            $random_number = rand(0,10);
            if ($random_number > 5) {
                ?>
                <a href="shorts.php?recept_id=<?= $item["id"] ?>" class="category-item" data-aos="fade-up" data-aos-delay="0">
                    <div class="category-header">
                        <img src="<?php echo $item['img']; ?>" alt="Category Icon">
                    </div>
                    <h2 class="category-title"><?php echo $item['title']; ?></h2>
                    <p class="category-description">
                        <?php echo $item['description']; ?>
                    </p>
                </a>
            <?php
            }
        }
        ?>
    </div>

</div>


<div id="Journal">
    <h1 data-aos="fade-down">Journal</h1>

    <div id="journal-main-container">
        <div id="journal-news-container" data-aos="zoom-in-up">
            <img src="images/journal/sushi.jpg" alt="placeholder">
            <h1>Tokyo, Japan 🇯🇵 – Sushi Reinvented</h1>
            <p>Discover the art of sushi beyond the classics. Local chefs are experimenting with fusion rolls featuring tropical fruits, truffle oil, and even wagyu beef. Tip: Try the limited “Cherry Blossom Roll” before it’s gone!</p>
        </div>

        <div id="info-cards-container">
            <div class="info-card" data-aos="zoom-in-left" data-aos-delay="100">
                <img src="images/journal/tagine.jpg">
                <h2>Marrakech, Morocco 🇲🇦 – Spice Symphony</h2>
                <p>Tagines, couscous, and mint tea! Moroccan markets overflow with colorful spices that awaken your senses. Secret riads mix tradition with modern twists.</p>
            </div>
            <div class="info-card" data-aos="zoom-in-right" data-aos-delay="200">
                <img src="images/journal/taco_truck.jpg">
                <h2>Mexico City, Mexico 🇲🇽 – Street Food Wonders</h2>
                <p>Tacos aren’t just food—they tell a story. From al pastor at sunrise to churros at sunset, Mexico City’s street food is bold, vibrant, and unforgettable.</p>
            </div>
        </div>
    </div>
</div>


<script type="module" src="./js/search.js"></script>

<?php
include_once './footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true 
  });
</script>
