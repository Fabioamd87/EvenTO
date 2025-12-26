<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $action = $_POST['action'];
    
    if ($_POST['with_car']){$with_car = $_POST['with_car'];}else{$with_car=null;};

    switch ($action){
        case 'join':
            if ($with_car == 'true'){
                header('Location: car.php?id='. $event_id);
                exit();
            }else{
                $stmt = $pdo->prepare("INSERT INTO partecipations (user_id, event_id) VALUES (?, ?)");
                try {
                    $stmt->execute([$user_id,$event_id]);
                    header('Location: event.php?id='. $event_id);
                    exit();
                } catch (PDOException $e) {
                    $error = 'Errore.';
                }
            }
            break;
        case 'leave':
            if ($with_car == 'true'){
                #check if the user has passangers
                $stmt = $pdo->prepare("SELECT user_id, car_id FROM partecipations WHERE car_id = ?");
                $stmt->execute([$user_id]);
                $car_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($car_users <= 1){
                    #delete user paticipation
                    $stmt = $pdo->prepare("DELETE FROM partecipations WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    header('Location: event.php?id='. $event_id);
                    exit();
            }
            else{
                #user is a passanger
                $stmt = $pdo->prepare("DELETE FROM partecipations WHERE user_id = ?");
                $stmt->execute([$user_id]);
                header('Location: event.php?id='. $event_id);
                exit();                
            }
            }else{
                try {
                    $stmt = $pdo->prepare("DELETE FROM partecipations WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    header('Location: event.php?id='.$event_id);
                    exit();
                } catch (PDOException $e) {
                    $error = 'Errore.';
                }
            }
            break;
        case 'delete':
            try {
                $stmt = $pdo->prepare("DELETE * FROM partecipations WHERE event_id = ?");
                $stmt->execute([$event_id]);
            } catch (PDOException $e) {
                $error = 'Errore.';
            }    
            try {
                $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
                $stmt->execute([$event_id]);        
                header('Location: events.php');
            } catch (PDOException $e) {
                $error = 'Errore.';
            }  
            break;
    }



}
?>