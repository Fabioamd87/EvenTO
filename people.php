<?php include 'db.php';
session_start();

if (isset($_SESSION['user'])) {
       
    $stmt = $pdo->prepare("SELECT id, name, city FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        echo '<a href="user.php?id='.$user['id'].'"> ', $user['name'].'</a> ';
        echo $user['city'].' </br>';
    }

    echo '<p><a href="index.php">Homepage</a></p>';
    echo '<p><a href="logout.php">Logout</a></p>';
}
else{
    header('Location: index.php');
    exit();
}
?>