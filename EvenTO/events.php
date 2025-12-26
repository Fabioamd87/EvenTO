<?php
include 'db.php';
session_start();

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

echo '<h1> Eventi:</h1>
<table>
<tr>
<th>Nome</th>
<th>Data</th>
<th>Organizzatore</th>
<th>Luogo</th>
</tr>';

#show events
foreach ($events as $event) {
    #print_r($events);
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$event['organizer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<tr>';
    echo '<td><a href="event.php?id='.$event['id'].' "> ',$event['name'],' </a></td>';
    echo '<td>',$event['date'],'</td>';
    if ($user){echo '<td>',$user['name'],'</td>';}else{echo '<td>?</td>';}
    echo '<td>',$event['city'],'</td>';
    echo '</tr>';
}
?>
</table>

<h2>Crea Evento</h2>
<form action="new_event.php" method="POST">
    <input type="text" name="name" placeholder="Nome" required><br>
    <input type="text" name="city" placeholder="Città" required><br>
    <input type="date" name="date" placeholder="Data"><br>
    <label>Con auto?:</label>
    <select name="with_car" id="event_type">
        <option value="true">Si</option>
        <option value="false">No</option>
    </select><br>
    <button type="submit">Invia</button>
</form>

<p><a href="index.php">Homepage</a></p>
<p><a href="logout.php">Logout</a></p>

<?php if (isset($error)) echo "<p>$error</p>"; ?>