<?php
include_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Calculate age if birthday is set
$age = null;
if ($user && !empty($user['birthday'])) {
    $birthday = strtotime($user['birthday']);
    if ($birthday) {
        $now = time();
        $datediff = $now - $birthday;
        $age = intval($datediff * 0.00000003170979);
    }
}

// Check user avatar
$picturepath = null;
if ($user) {
    $possible_paths = [
        "userpics/user-" . $user['id'],
        "userpics/user-" . $user['id'] . ".jpg",
        "userpics/user-" . $user['id'] . ".png",
        "userpics/user-" . $user['id'] . ".jpeg"
    ];
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $picturepath = $path;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO <?= $user ? '- ' . htmlspecialchars($user['name']) : '' ?></title>

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
        <?php if (!$user): ?>
            <h1>Utente non trovato</h1>
            <p><a href="people.php">Torna alla lista persone</a></p>
        <?php else: ?>
            <header class="event-header">
                <h1><?= htmlspecialchars($user['name']) ?></h1>
            </header>

            <section class="event-layout" style="margin-top: 1.5rem;">
                <article class="event-content">
                    <section class="event-section">
                        <div class="event-card">
                            <?php if ($picturepath): ?>
                                <div style="margin-bottom: 1.5rem; text-align: center;">
                                    <img src="<?= htmlspecialchars($picturepath) ?>" alt="Foto profilo" width="160" height="160" style="border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                                </div>
                            <?php endif; ?>

                            <div class="event-info">
                                <?php if (!empty($user['city'])): ?>
                                    <div>📍 <strong>Città:</strong> <?= htmlspecialchars($user['city']) ?></div>
                                <?php endif; ?>

                                <?php if ($age !== null): ?>
                                    <div>🎂 <strong>Età:</strong> <?= $age ?> anni</div>
                                <?php endif; ?>

                                <?php if (!empty($user['telegram'])): ?>
                                    <div>✈️ <strong>Telegram:</strong> <a href="https://t.me/<?= htmlspecialchars($user['telegram']) ?>" target="_blank">@<?= htmlspecialchars($user['telegram']) ?></a></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </article>

                <aside class="event-sidebar">
                    <section class="event-section">
                        <a href="people.php" class="button" style="display: block; text-align: center; width: 100%;">← Torna a Persone</a>
                    </section>
                </aside>
            </section>
        <?php endif; ?>
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