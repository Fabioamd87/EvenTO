<?php include_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['user'])): ?>

<nav>
    <ul>
        <li><a href="events.php">Eventi</a></li>
        <li><a href="people.php">Persone</a></li>
        <li><a href="myprofile.php">
                <?php echo $_SESSION['name']; ?>
        </a></li>
    </ul>
</nav>

<div class="buttons">
        <a href="logout.php" class="button primary">Logout</a>
</div>

<?php else: ?>

        <div class="buttons">
                <a href="login.html" class="button primary">Login</a>
                <a href="register.php" class="button">Register</a>
        </div>

<?php endif; ?>