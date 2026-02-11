<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to EvenTO</title>
</head>
<body>

<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $instagram = $_POST['instagram'];
    $city = $_POST['city'];
    $birthday = $_POST['birthday'];

    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, instagram, city, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$email, $password, $name, $instagram, $city, $birthday]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Email già in uso.';
    }
}
?>

<h2>Registrati</h2>
<form method="POST">
    <label for="email">Email*: </label> <input type="text" id="email" name="email" placeholder="Email *" required><br>
    <label for="password">Password*: </label> <input type="password" id="password" name="password" placeholder="Password *" required><br>
    <label for="name">Nome*: </label> <input type="text" id="name" name="name" placeholder="Nome *" required><br>
    <label for="city">Città*: </label> <input type="text" id="city" name="city" placeholder="Città *" required><br>
    <label for="date">Data di Nascita: </label> <input type="date" id="birthday" name="birthday" placeholder="Data di nascita"><br>
    <label for="instagram">Instagram: </label> <input type="text" id="instagram" name="instagram" placeholder="Instagram"><br><br>
    <label for="nome">*Campi Obbligatori</label><br><br>
    <button type="submit">Registrati</button>
</form>


<?php if (isset($error)) echo "<p>$error</p>"; ?>

</body>
</html>