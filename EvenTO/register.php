<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $telegram = $_POST['telegram'];
    $city = $_POST['city'];
    $birthday = $_POST['birthday'];

    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, telegram, city, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$email, $password, $name, $telegram, $city, $birthday]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Email già in uso.';
    }
}
?>

<h2>Registrati</h2>
<form method="POST">
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="name" name="name" placeholder="Nome" required><br>
    <input type="text" name="telegram" placeholder="Telegram"><br>
    <input type="text" name="city" placeholder="Città" required><br>
    <input type="date" name="birthday" placeholder="Data di nascita"><br>    
    <button type="submit">Registrati</button>
</form>

<?php if (isset($error)) echo "<p>$error</p>"; ?>