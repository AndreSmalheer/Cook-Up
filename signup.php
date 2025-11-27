<?php
include_once './header.php';
?>

    <div class="wrapper">

        <section class="signup-form">

            <h2>Sign Up</h2>

            <form action="./includes/signup.inc.php" method="post">

                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Full name...">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Email...">

                <label for="uid">Username:</label>
                <input type="text" name="uid" id="uid" placeholder="Username...">

                <label for="pwd">Password:</label>
                <input type="password" name="pwd" id="pwd" placeholder="Password...">

                <label for="pdwrepeat">Repeat password:</label>
                <input type="password" name="pwdrepeat" id="pdwrepeat" placeholder="Repeat password...">

                <button type="submit" name="submit" id="submit">Sign Up</button>

            </form>
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] === 'emptyinput') {
                    echo "<p>Fill in all fields!</p>";
                }
                else if ($_GET['error'] === 'invaliduid') {
                    echo "<p>Choose a proper username!</p>";
                }
                else if ($_GET['error'] === 'invalidemail') {
                    echo "<p>Choose a proper email!</p>";
                }
                else if ($_GET['error'] === 'passwordsdontmatch') {
                    echo "<p>Passwords doesn't match!</p>";
                }
                else if ($_GET['error'] === 'stmtfailed') {
                    echo "<p>Something went wrong, try again!</p>";
                }
                else if ($_GET['error'] === 'usernametaken') {
                    echo "<p>Username already taken!</p>";
                }
                else if ($_GET['error'] === 'none') {
                    echo "<p>You have signed up!</p>";
                }
            }
            ?>
        </section>


<?php
    include_once './footer.php';
?>