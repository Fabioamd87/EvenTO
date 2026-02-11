<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persone</title>
</head>
<body>

<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_GET['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2><?php echo htmlspecialchars($user['name']); ?></h2>

<?php
// Check if file already exists
$picturepath = "userpics/user-".$user["id"].'.jpg';

if (file_exists($picturepath)) {
    echo '<p><img src="',$picturepath,'" width="256" style="border-radius: 50%;"></p>';
    $uploadOk = 0;
  }
?>
<p>❤️</p>
<p>Città: <?php echo htmlspecialchars($user['city']); ?></p>

<!-- calcolo età -->
<?php
$birthday = strtotime($user['birthday']);
$now = time();
$datediff = $now - $birthday;
$age= intval($datediff*0.00000003170979);
?>

<p>Età: <?php echo $age; ?> </p>

<a href="index.php">Home</a>
<a href="logout.php">Logout</a>
</body>
</html>