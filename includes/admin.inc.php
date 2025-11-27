<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Cook Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="../images/logo.png">

    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="../styles/components/header.css">
</head>
<body>

<div id="header">
    <div id="logo-container">
        <img id="logo" src="../images/logo.png" alt="Logo">
    </div>

    <h1 id="site-title">Cook up</h1>

    <ul id="nav-menu">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../overview.php?catogory_id=1&catogory_name=Breakfast">Browse</a></li>
        <li><a href="../shorts.php">Watch</li>
    </ul>

    <div id="account-section">
        <?php
        if (isset($_SESSION['useruid'])) {
            if ($_SESSION['role'] === 'user') {
                echo "<button id='profile-btn'><a href='../profile.php'>Profile page</a></button>";
                echo "<button id='logout-btn'><a href='../includes/logout.inc.php'>Log out</a></button>";
            }
            if ($_SESSION['role'] === 'admin') {
                echo "<button id='admin-btn'><a href='../admin/admin.php'>Admin page</a></button>";
                echo "<button id='logout-btn'><a href='../includes/logout.inc.php'>Log out</a></button>";
            }
        }
        else {
            echo "<button id='signup-btn'><a href='../signup.php'>Sign up</a></button>";
            echo "<button id='login-btn'><a href='../login.php'>Login</a></button>";
        }
        ?>
    </div>
</div>

<div id="mobile-header">
    <img id="logo" src="../images/logo.png" alt="Logo">
    <div class="header_hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<div id="side_menu">
    <div class="header_hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-menu">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../overview.php?catogory_id=1&catogory_name=Breakfast">Browse</a></li>
        <li><a href="../shorts.php">Watch</li>
        <?php
        if (!isset($_SESSION['useruid'])) {
            echo "<li><a href='../login.php'>Login</a></li>";
            echo "<li><a href='../signup.php'>Sign Up</a></li>";
        }
        ?>
    </ul>
</div>



<ul id="header_ul">
    <li><a href="../includes/logout.inc.php">Logout</a></li>
</ul>

<script>
    const hamburgers = document.querySelectorAll('.header_hamburger');
    const sideMenu = document.getElementById('side_menu');
    const mobileQuery = window.matchMedia("(max-width: 768px)");

    hamburgers.forEach(hamburger => {
        hamburger.addEventListener('click', () => {
            sideMenu.classList.toggle('show');
            hamburger.classList.toggle('open');
        });
    });

    document.addEventListener('pointerdown', (event) => {
        if (!mobileQuery.matches) return;

        const isHamburger = event.target.closest('.header_hamburger');
        const isInsideMenu = event.target.closest('#side_menu');

        if (!isHamburger && !isInsideMenu) {
            sideMenu.classList.remove('show');
        }
    });
</script>

    <div class="admin-shell">
        <aside class="admin-sidebar" aria-label="Admin navigatie">
            <div class="sidebar-top">
                <img src="../images/logo.png" alt="CookUp" class="sidebar-logo">
                <h3 class="sidebar-title">Admin Paneel</h3>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="sidebar-link active" data-target="users-section" id="users-section">
                        <span class="icon">👥</span><span class="label">Gebruikers</span>
                    </li>
                    <li class="sidebar-link" data-target="recipes-section" id="recipes-section">
                        <span class="icon">🍽️</span><span class="label">Recepten</span>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-bottom">
                <a href="../includes/logout.inc.php" class="sidebar-logout">Logout</a>
            </div>
        </aside>

        <main class="admin-main">
            <div class="wrapper">
                <h1 id="headerTable">Gebruikers</h1>

                <table id="usersTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>
                        <th>Email</th>
                        <th>Gebruikersnaam</th>
                        <th>Rol</th>
                        <th>Acties</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['usersId']) ?></td>
                                <td><?= htmlspecialchars($item['usersName']) ?></td>
                                <td><?= htmlspecialchars($item['usersEmail']) ?></td>
                                <td><?= htmlspecialchars($item['usersUid']) ?></td>
                                <td><?= htmlspecialchars($item['role']) ?></td>
                                <td class="actieknoppen">
                                    <a href="../admin/admin_users/bewerkpage/bewerk.php?id=<?=urlencode($item['usersId'])?>" class="btn btn-bewerken">Bewerken</a>
                                    <a href="../admin/admin_users/verwijderpage/verwijderen.php?id=<?=urlencode($item['usersId'])?>" class="btn btn-verwijderen">Verwijderen</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Geen gebruikers gevonden.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <table id="recipesTable">
                    <thead>
                    <tr>
                        <th>recipeId</th>
                        <th>usersId</th>
                        <th>title</th>
                        <th>description</th>
                        <th>categoryId</th>
                        <th>createdAt</th>
                        <th>updatedAt</th>
                        <th>Acties</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (!empty($recipes)): ?>
                        <?php foreach ($recipes as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['recipeId']) ?></td>
                                <td><?= htmlspecialchars($item['usersId']) ?></td>
                                <td><?= htmlspecialchars($item['title']) ?></td>
                                <td><?= htmlspecialchars($item['description']) ?></td>
                                <td><?= htmlspecialchars($item['categoryId']) ?></td>
                                <td><?= htmlspecialchars($item['createdAt']) ?></td>
                                <td><?= htmlspecialchars($item['updatedAt']) ?></td>
                                <td class="actieknoppen">
                                    <a href="../admin/admin_recipes/bewerkpage/bewerk.php?id=<?=urlencode($item['recipeId'])?>" class="btn btn-bewerken">Bewerken</a>
                                    <a href="../admin/admin_recipes/verwijderpage/verwijderen.php?id=<?=urlencode($item['recipeId'])?>" class="btn btn-verwijderen">Verwijderen</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Geen gebruikers gevonden.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>


</body>
<script src="../js/header.js"></script>
<script src="../js/admin.js"></script>
</html>

