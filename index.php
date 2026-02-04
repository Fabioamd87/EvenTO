<?php include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to EvenTO</title>
</head>
<body>
<h1>Conoscersi con EvenTO🎉</h1>
<p>Portale per organizzare eventi e conoscenze.</p>

<?php
if (isset($_SESSION['user'])) {
    echo 'Benvenuto ', htmlspecialchars($_SESSION['user']);
    
    echo '<p><a href="myprofile.php">Profilo</a></p>';
    echo '<p><a href="events.php">Eventi</a></p>';
    echo '<p><a href="people.php">Persone</a></p>';
    echo '<p><a href="logout.php">Logout</a></p>';
}
else{
    echo '<a href="login.php">Login</a> | <a href="register.php">Registrati</a>';
}
?>
</body>
</html>