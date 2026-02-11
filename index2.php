<?php include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizza un EvenTO</title>
</head>
<style>
        .login-container {
            background: #fff;
            padding: 25px;
            width: 90%;
            max-width: 360px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .buttons {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
</style>
<body>
<div>
    <h1>EvenTO🎉</h1>
</div>
<div>
    <p>Portale per organizzare eventi e conoscersi.</p>
</div>
<div class="login-container">
    <?php include("menu.php"); ?>
</div>
</body>
</html>