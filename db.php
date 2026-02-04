<?php
// includes/db.php

$dsn = 'sqlite:' . __DIR__ . '/database.db';
try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creazione tabella utenti se non esiste
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE,
        password TEXT,
        name TEXT,
        telegram TEXT,
        city TEXT,
        birthday DATE
    )");

    // Creazione tabella eventi se non esiste
    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        organizer_id TEXT,
        name TEXT,
        with_car TEXT,
        city TEXT,
        date TEXT
    )");

    // Creazione tabella eventi se non esiste
    $pdo->exec("CREATE TABLE IF NOT EXISTS partecipations (
        user_id INT,
        event_id INT,
        with_car TEXT,
        seat INT,
        car_id INT,
        PRIMARY KEY (user_id, event_id),
        FOREIGN KEY (car_id) REFERENCES users(id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
    )");

    // Creazione tabella eventi se non esiste
    $pdo->exec("CREATE TABLE IF NOT EXISTS cars (
        owner_id INT,
        seats INT,
        PRIMARY KEY (owner_id),
        FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
    )");

} catch (PDOException $e) {
    echo 'Errore connessione al database: ' . $e->getMessage();
    exit();
}
?>