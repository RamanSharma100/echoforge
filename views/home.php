<?php
if (
    isset($_SESSION['user'])
) {
?>
    <h1>
        Welcome to the home page, <?= $_SESSION['user']['name'] ?>
    </h1>
    <a href="/logout">Logout</a>
<?php
} else {
?>

    <h1>
        Welcome to the home page
    </h1>
    <a href="/login">Login</a>
<?php
}
?>