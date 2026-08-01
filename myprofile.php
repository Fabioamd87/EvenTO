<?php
include_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

# Delete account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    try {
        $stmt->execute([$user['id']]);
        session_destroy();
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Impossibile cancellare l\'account.';
    }
}

// Calculate age if birthday is set
$age = null;
if (!empty($user['birthday'])) {
    $birthday = strtotime($user['birthday']);
    if ($birthday) {
        $now = time();
        $datediff = $now - $birthday;
        $age = intval($datediff * 0.00000003170979);
    }
}

// Check user avatar
$picturepath = null;
$possible_paths = [
    "userpics/user-" . $user['id'],
    "userpics/user-" . $user['id'] . ".jpg",
    "userpics/user-" . $user['id'] . ".png",
    "userpics/user-" . $user['id'] . ".jpeg"
];
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        $picturepath = $path;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO - Il mio Profilo</title>

    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
        integrity="sha256-gvEnj2axkqIj4wbYhPjbWV7zttgpzBVEgHub9AAZQD4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="events.css">
    <link rel="stylesheet" href="css/croppie.min.css" />

    <style>
        .croppie-container .cr-boundary {
            border-radius: 12px;
            border: 1px solid #e0e5eb;
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin: 0 auto;
        }
        .upload-demo-wrap {
            display: none;
            margin-top: 15px;
        }
        .profile-avatar-wrapper {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .profile-avatar-img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #36bae6;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                EvenTO
            </div>

            <input class="hamburger-button" type="checkbox" id="hamburger-button" />
            <label for="hamburger-button">
                <div></div>
            </label>

            <div class="menu">
                <?php include("menu.php"); ?>
            </div>
        </div>
    </header>

    <div class="page-content">
        <header class="event-header">
            <h1><?= htmlspecialchars($user['name']) ?></h1>
            <p class="event-subtitle">Profilo personale</p>
        </header>

        <section class="event-layout" style="margin-top: 1.5rem;">
            <article class="event-content">
                <section class="event-section">
                    <div class="event-card">
                        <div class="profile-avatar-wrapper">
                            <div id="profile-avatar-container" style="<?= $picturepath ? '' : 'display: none;' ?>">
                                <img id="profile-avatar-img" class="profile-avatar-img" src="<?= $picturepath ? htmlspecialchars($picturepath) : '' ?>" alt="Foto profilo">
                            </div>

                            <div style="margin-top: 1rem;">
                                <label for="upload" class="button" style="cursor: pointer;">
                                    📷 Modifica Foto Profilo
                                </label>
                                <input type="file" id="upload" accept="image/*" style="display: none;" />
                            </div>

                            <div class="upload-demo-wrap">
                                <div id="upload-demo"></div>
                                <button class="upload-result button primary" style="display: none; margin: 15px auto 0 auto;">💾 Salva Foto</button>
                                <div id="upload-status" style="margin-top: 10px; font-weight: 500;"></div>
                            </div>
                        </div>

                        <div class="event-info" style="border-top: 1px solid #eee; padding-top: 1.5rem;">
                            <?php if (!empty($user['city'])): ?>
                                <div>📍 <strong>Città:</strong> <?= htmlspecialchars($user['city']) ?></div>
                            <?php endif; ?>

                            <?php if ($age !== null): ?>
                                <div>🎂 <strong>Età:</strong> <?= $age ?> anni</div>
                            <?php endif; ?>

                            <?php if (!empty($user['telegram'])): ?>
                                <div>✈️ <strong>Telegram:</strong> <a href="https://t.me/<?= htmlspecialchars($user['telegram']) ?>" target="_blank">@<?= htmlspecialchars($user['telegram']) ?></a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </article>

            <aside class="event-sidebar">
                <section class="event-section">
                    <div class="event-card">
                        <h3>Gestione Account</h3>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 1rem;">
                            <a href="logout.php" class="button primary" style="text-align: center;">Logout</a>
                            <form method="POST" onsubmit="return confirm('Sei sicuro di voler cancellare il tuo account?');">
                                <input type="hidden" name="action" value="delete_account">
                                <button type="submit" class="button" style="width: 100%; background: #e74c3c; color: white;">Cancella account</button>
                            </form>
                        </div>
                    </div>
                </section>
            </aside>
        </section>
    </div>

    <footer>
        Footer
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

    <script>
        const hamburgerButton = document.getElementById('hamburger-button');
        const headerLinks = document.querySelectorAll('header .container .menu a');

        headerLinks.forEach((link) => {
            link.addEventListener('click', (e) => hamburgerButton.checked = false);
        });

        $(document).ready(function(){
            var $uploadCrop;

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 180,
                    height: 180,
                    type: 'circle'
                },
                boundary: {
                    width: 260,
                    height: 260
                },
                enableExif: true
            });

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.upload-demo-wrap').slideDown(200);
                        $('.upload-result').show();
                        $('#upload-status').text('');
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        });
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#upload').on('change', function () { readFile(this); });

            $('.upload-result').on('click', function (ev) {
                ev.preventDefault();
                var $btn = $(this);
                $btn.text('Salvataggio...').prop('disabled', true);

                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    $('#profile-avatar-img').attr('src', resp);
                    $('#profile-avatar-container').show();
                    
                    fetch("upload_userpic.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ image: resp })
                    })
                    .then(response => response.text())
                    .then(data => {
                        $('#upload-status').css('color', '#27ae60').text('Foto salvata con successo!');
                        $btn.text('💾 Salva Foto').prop('disabled', false);
                        setTimeout(function() {
                            $('.upload-demo-wrap').slideUp(200);
                            $btn.hide();
                        }, 1200);
                    })
                    .catch(error => {
                        $('#upload-status').css('color', '#e74c3c').text('Errore nel salvataggio.');
                        $btn.text('💾 Salva Foto').prop('disabled', false);
                    });
                });
            });
        });
    </script>
</body>

</html>