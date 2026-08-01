<?php
include_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id, name, city FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO - Persone</title>

    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
        integrity="sha256-gvEnj2axkqIj4wbYhPjbWV7zttgpzBVEgHub9AAZQD4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="events.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                EvenTO
            </div>

            <input class="hamburger-button" type="checkbox" id="hamburger-button" />
            <label for="hamburger-button">
                <div></div>
            </label>

            <div class="menu">
                <?php include("menu.php"); ?>
            </div>
        </div>
    </header>

    <div class="page-content">
        <h1>Lista Persone</h1>

        <div class="events" style="margin-top: 1.5rem;">
            <?php foreach ($users as $user): ?>
                <article class="event-card">
                    <h3>
                        <a href="user.php?id=<?= (int)$user['id'] ?>">
                            <?= htmlspecialchars($user['name']) ?>
                        </a>
                    </h3>

                    <div class="meta">
                        <?php if (!empty($user['city'])): ?>
                            <span>
                                📍 <?= htmlspecialchars($user['city']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        Footer
    </footer>

    <script>
        const hamburgerButton = document.getElementById('hamburger-button');
        const headerLinks = document.querySelectorAll('header .container .menu a');

        headerLinks.forEach((link) => {
            link.addEventListener('click', (e) => hamburgerButton.checked = false);
        })
    </script>
</body>

</html>