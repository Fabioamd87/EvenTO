<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO</title>

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
            <h1>Lista Eventi</h1>
           
            <?php
                include 'db.php';
                // session_start();

                #get logged user info to create events
                if (!isset($_SESSION['user'])) {
                    header('Location: index.php');
                    exit();
                }

                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$_SESSION['user']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo->prepare("SELECT * FROM events");
                $stmt->execute();
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            
            <p>
            <div class="events">

            <?php foreach ($events as $event):
                $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
                $stmt->execute([$event['organizer_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user){
                    $organizer = $user;
                }
                else{
                    $organizer['name'] = '';
                }
            ?>
                <article class="event-card">

                    <h3>
                        <a href="event.php?id=<?= (int)$event['id'] ?>">
                            <?= htmlspecialchars($event['name']) ?>
                        </a>
                    </h3>

                    <div class="meta">
                        <span>
                            📅 <?= date('d/m/Y', strtotime($event['date'])) ?>
                        </span>
                        <span>
                            📍 <?= htmlspecialchars($event['city']) ?>
                        </span>
                        <span>
                            👤 <?= htmlspecialchars($organizer['name']) ?>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>

            </div>
            </p>

            <h2>Crea Evento</h2>
            <form action="new_event.php" method="POST">
                <input type="text" name="name" placeholder="Nome" required><br>
                <input type="text" name="city" placeholder="Città" required><br>
                <input type="date" name="date" placeholder="Data"><br>
                <label>Con auto?:</label>
                <select name="car_needed" id="event_type"> <!--rename in car needed -->
                    <option value="false">No</option>
                    <option value="true">Si</option>
                </select><br>
                <button type="submit">Invia</button>
            </form>

            <?php if (isset($error)) echo "<p>$error</p>"; ?>

    <footer>
        Footer
    </footer>

    <script>
        /*
            THIS IS NOT NECESSARY ON REGULAR WEBSITES
 
            If you're using a library like React, you'll need the mobile menu to close once a link has been clicked.
            That's all the below code does.
        */

        const hamburgerButton = document.getElementById('hamburger-button');
        const headerLinks = document.querySelectorAll('header .container .menu a');

        headerLinks.forEach((link) => {
            link.addEventListener('click', (e) => hamburgerButton.checked = false);
        })
    </script>
</body>

</html>