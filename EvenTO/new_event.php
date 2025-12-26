<?php
include 'db.php';
session_start();

#get logged user info to create events
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}else{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    #get user from session
    
    $name = $_POST['name'];
    $city = $_POST['city'];
    $date = $_POST['date'];
    $with_car = $_POST['with_car'];

    $stmt = $pdo->prepare("INSERT INTO events (organizer_id, name, with_car, city, date) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$user['id'], $name, $with_car, $city, $date]);
        header('Location: events.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Errore di inserimento.';
    }
}
?>