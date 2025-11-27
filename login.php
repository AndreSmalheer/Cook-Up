<?php
include_once './header.php';
?>

    <div class="wrapper">

        <section class="signup-form">

            <h2>Login</h2>

            <form action="./includes/login.inc.php" method="post">

                <label for="uid">Username/Email:</label>
                <input type="text" name="uid" id="uid" placeholder="Username/Email...">

                <label for="pwd">Password:</label>
                <input type="password" name="pwd" id="pwd" placeholder="Password...">

                <button type="submit" name="submit" id="submit">Login</button>

            </form>
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] === 'emptyinput') {
                    echo "<p>Fill in all fields!</p>";
                }
                else if ($_GET['error'] === 'wrongloginusername') {
                    echo "<p>Incorrect login username information!</p>";
                }
                else if ($_GET['error'] === 'wrongloginpassword') {
                    echo "<p>Incorrect login password information!</p>";
                }
                else if ($_GET['error'] === 'not_logged_in') {
                    echo "<p>You are not logged in!</p>";
                }
            }
            ?>
        </section>


<?php
include_once './footer.php';
?>