<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    #get event info
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
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
    ?>

    <h2>Evento: <?php echo $event['name']; ?></h2>

    <?php
    if ($user['id'] == $event['organizer_id']){
        echo "<h4>Sei l'organizzatore</h4>";
    } else{
        #get organizer info
    }
    ?>

    <p>Luogo: <?php echo $event['city']; ?></p>
    <p>Data: <?php echo $event['date']; ?></p>

    Lista Partecipanti:
    <?php 
    $stmt = $pdo->prepare(
        "SELECT id,name,with_car,seats,car_id
        FROM users
        JOIN partecipations ON users.id = partecipations.user_id
        WHERE partecipations.event_id = ?");
    $stmt->execute([$event['id']]);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <ul>
    <?php
    $participans_ids = [];
    if ($participants){
        foreach ($participants as $participant){
            if ($participant['id'] == $participant['car_id']){
                echo '<li><a href="user.php?id='.$participant['id'].'"> ', $participant['name'].'</a> 🚗</li>';
                $seats = $participant['seats'];
                #show passangers
                echo '<ul>';
                $passangers = 0;
                foreach ($participants as $passanger){
                    if ($passanger['car_id'] == $participant['id'] AND $passanger['id'] != $participant['id']){
                        echo '<li><a href="user.php?id='.$passanger['id'].'"> ',$passanger['name'],'</a></li>';
                        $seats--;
                        $passangers++;
                    }
                }
                #show empty seats
                $free_seats = $seats - $passangers;
                if ($seats > $passangers){
                    for ($i = 1; $i <= $free_seats ; $i++) {
                        echo '<li>',$i,'</li>';
                    }
                    echo '</ul>';
                }
                else{
                    echo '</ul>';                   
                }
            }
            elseif($participant['with_car'] == 'false' AND $participant['car_id'] == ''){
                echo '<li><a href="user.php?id='.$participant['id'].'"> ', $participant['name'].'</a></li>';
            }
            #used to check if a user is already participating
            $participans_ids[] = $participant['id'];
        }
    } else{
        echo 'Nessun partecipante';
    }
    ?>

    </ul>
    <form action="eventactions.php" method="POST" >
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <input type="hidden" name="event_id" value="<?php echo $_GET['id']; ?>">
        <input type="hidden" name="with_car" value="<?php echo $event['with_car']; ?>">
        
        <?php
        #check if the user is already in the participants list
        if (!in_array($user['id'], $participans_ids)){
            echo '<button type="submit" name="action" value="join">Partecipa</button>';
        }
        else{
            echo '<button type="submit" name="action" value="leave">Cancellati</button>';
        }
        ?>
        
        <?php
        echo '<br><br>';
        #if the user is the organizer he can cancel the event
        if ($user['id'] == $event['organizer_id']){
            echo '<button type="submit" name="action" value="delete">Cancella Evento</button>';
        }
}        
?>

</form>
<p><a href="events.php">Torna agli eventi</a></p>
<a href="index.php">Home</a>
<a href="logout.php">Logout</a>