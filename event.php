<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO</title>
    <!-- si puo mettere il titolo della pagina con il nome dell evento -->

    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
        integrity="sha256-gvEnj2axkqIj4wbYhPjbWV7zttgpzBVEgHub9AAZQD4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="event.css">    
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

    <?php
    include 'db.php';
    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
    ?>
    

    <div class="page-content">

    <main class="event-page">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        #get event info
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>

    </ul>
    <header class="event-header">

        <h1>
            <?= htmlspecialchars($event['name']) ?>
        </h1>

        <p class="event-subtitle">
            Evento pubblico
        </p>

    </header>

    <section class="event-layout">
        <article class="event-content">
            <section class="event-section">

            <div class="event-info">
                <?php
                $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
                $stmt->execute([$event['organizer_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user){
                    $organizer = $user;
                }
                else{
                    $organizer = '';
                }


                #if the event does not exist we show an error message
                if (!$event) {
                    echo 'Evento non esistente';
                }
                else{

                    #get user info
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$_SESSION['user']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    #get organizer info
                    if ($user['id'] == $event['organizer_id']){
                        echo "<h4>Sei l'organizzatore</h4>";
                    } else{}

                    $stmt = $pdo->prepare(
                        "SELECT id,name,with_car,seats,car_id
                        FROM users
                        JOIN partecipations ON users.id = partecipations.user_id
                        WHERE partecipations.event_id = ?");
                    $stmt->execute([$event['id']]);
                    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

                #incollato da giu in modo da avere la variabile $participants_ids[]
                $participants_ids = [];
                if ($participants){
                    foreach ($participants as $participant){
                        #partecipante guidante new code
                        $participants_ids[] = $participant['id'];}
                }?>

                <div>📅 <?= date('d/m/Y', strtotime($event['date'])) ?></div>
                <div>📍 <?= htmlspecialchars($event['city']) ?></div>
                <div>👤 <?= htmlspecialchars($organizer['name']) ?></div>
                <?php echo $event['car_needed']; ?>
                <?php if (!empty($event['car_needed']) && ($event['car_needed'] === 'true' || $event['car_needed'] === true || $event['car_needed'] == '1')): ?>
                    <div>🚗 Auto necessaria</div>
                <?php endif; ?>
            </div></section>

        <section class="event-section event-description">
            <h2>Descrizione</h2>
            <p>
            <?= nl2br(
                htmlspecialchars($event['name'])
            )?>
            </p>
    </section>

    <?php
    $cars = [];
    $waiting = [];
    $participants_ids = [];

    if ($participants) {
        foreach ($participants as $participant) {
            $participants_ids[] = $participant['id'];

            if ($participant['id'] == $participant['car_id']) {
                $cars[$participant['id']] = [
                    'driver' => $participant,
                    'passengers' => [],
                    'free_seats' => $participant['seats']
                ];
            }
        }

        foreach ($participants as $participant) {
            if (isset($cars[$participant['car_id']]) && $participant['id'] != $participant['car_id']) {
                $cars[$participant['car_id']]['passengers'][] = $participant;
                $cars[$participant['car_id']]['free_seats']--;
            }
        }

        foreach ($participants as $participant) {
            if ($participant['with_car'] === 'false') {
                $waiting[] = $participant;
            }
        }
    }
    ?>

    <section class="event-section">
        <?php if (!empty($event['car_needed']) && ($event['car_needed'] === 'true' || $event['car_needed'] === true || $event['car_needed'] == 1)): ?>
            <p>Lista Macchine:</p>

            <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <div class="driver">
                        🚗 <a href="user.php?id=<?= (int)$car['driver']['id'] ?>">
                            <?= htmlspecialchars($car['driver']['name']) ?>
                        </a>
                    </div>

                    <ul>
                        <?php foreach ($car['passengers'] as $passenger): ?>
                            <li>
                                <a href="user.php?id=<?= (int)$passenger['id'] ?>">
                                    <?= htmlspecialchars($passenger['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>

                        <?php for ($i = 0; $i < $car['free_seats']; $i++): ?>
                            <li class="empty">Posto libero</li>
                        <?php endfor; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <p>In attesa di un passaggio:</p>

            <?php if ($waiting): ?>
                <?php foreach ($waiting as $person): ?>
                    <div class="car-card">
                        <a href="user.php?id=<?= (int)$person['id'] ?>">
                            <?= htmlspecialchars($person['name']) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nessuno in attesa.</p>
            <?php endif; ?>

        <?php else: ?>

            <h2>Lista Partecipanti (<?= count($participants) ?>)</h2>
            <?php if (!empty($participants)): ?>
                <ul>
                    <?php foreach ($participants as $participant): ?>
                        <li>
                            👤 <a href="user.php?id=<?= (int)$participant['id'] ?>">
                                <?= htmlspecialchars($participant['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nessun partecipante al momento.</p>
            <?php endif; ?>

        <?php endif; ?>
    </section>

        </article>
<aside class="event-sidebar">

<section class="event-section">

<h2>Partecipa</h2>

<form action="eventactions.php" method="POST">

    <!-- anche questo da togliere altrimenti uno potrebbe iscrivere altri con delle POST -->
    <input
        type="hidden"
        name="user_id"
        value="<?= (int)$user['id'] ?>">

    <input
        type="hidden"
        name="event_id"
        value="<?= (int)$event['id'] ?>">

    <?php if ((!in_array($user['id'], $participants_ids))): ?>

        <button
            class="event-button"
            type="submit"
            name="action"
            value="join">

            Partecipa

        </button>

    <?php else: ?>

        <button
            class="event-button"
            type="submit"
            name="action"
            value="leave">

            Cancella partecipazione

        </button>

    <?php endif; ?>


    <?php if ($user['id'] == $event['organizer_id']): ?>

        <button
            class="event-button danger"
            type="submit"
            name="action"
            value="delete">

            Cancella evento

        </button>

    <?php endif; ?>

</form>

</section>

</aside>
</section>
</main>


    
    
    <form action="eventactions.php" method="POST" >
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <input type="hidden" name="event_id" value="<?php echo $_GET['id']; ?>">
        <input type="hidden" name="car_needed" value="<?php echo $event['car_needed']; ?>">
        
        <?php
            #check if the user is already in the participants list
            if (!in_array($user['id'], $participants_ids)){
                echo '<button type="submit" name="action" value="join">Partecipa</button>';
            }
            else{
                echo '<button type="submit" name="action" value="leave">Cancellati</button>';
            }

            echo '<br><br>';
            #if the user is the organizer he can cancel the event
            if ($user['id'] == $event['organizer_id']){
                echo '<button type="submit" name="action" value="delete">Cancella Evento</button>';
            }
        }        
        ?>
    </form>