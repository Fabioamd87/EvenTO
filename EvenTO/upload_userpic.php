<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    die("Errore: dati non ricevuti o JSON non valido.");
}

if (!isset($data['image'])) {
    die("Errore: campo 'image' mancante.");
}

$base64_image = $data['image'];

// Verifica formato base64
if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
    $data = substr($base64_image, strpos($base64_image, ',') + 1);
    $ext = strtolower($type[1]); // jpg, png, gif, etc.

    $data = base64_decode($data);
    if ($data === false) {
        die("Errore nella decodifica base64.");
    }

    // Assicurati che la cartella "uploads/" esista e sia scrivibile
    if (!is_dir("userpics")) {
        mkdir("userpics", 0777, true);
    }

    //$fileName = 'userpics/user-' . $user["id"] . '.' . $ext;
    $fileName = 'userpics/user-' . $user["id"];
    file_put_contents($fileName, $data);

    echo "Immagine salvata: " . $fileName;
} else {
    die("Formato base64 non valido.");
}
