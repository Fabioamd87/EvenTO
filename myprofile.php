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

#delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?;");
    try {
        $stmt->execute([$user['id']]);
        session_destroy();
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Impossibile cancellare.';
    }
}?>


<link rel="stylesheet" href="css/croppie.min.css" />
<h2><?php echo $user['name']; ?></h2>

<?php
// Check if file already exists
//$picturepath = "userpics/user-".$user["id"].'.jpg';
$picturepath = "userpics/user-".$user["id"];

if (file_exists($picturepath)) {
    echo '<p><img src="',$picturepath,'" width="256"></p>';
    $uploadOk = 0;
  }
?>

<div class="demo-wrap upload-demo">
    <div class="container">
        <div class="pull-left">
            <div class="actions">
                <a class="btn file-btn">
                    <input type="file" id="upload" value="Выберите файл" accept="image/*" />
                </a>
                <button class="upload-result">Carica</button>
                <input type="hidden" id="userprofile-avatar" class="form-control" >
            </div>
        </div>
        <div class="pull-left">
            <div class="upload-msg">
                Upload an image
            </div>
            <div class="upload-demo-wrap">
                <div id="upload-demo"></div>
            </div>
        </div>
    </div>
</div>  

<div id="cropped-result">Result:<br><img></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

<script language="JavaScript" type="text/javascript">
$(document).ready(function(){

var $uploadCrop;

function readFile(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('.upload-demo').addClass('ready');
        $uploadCrop.croppie('bind', {
                url: e.target.result
        }).then(function(){
                console.log('jQuery bind complete');
        });

    }

    reader.readAsDataURL(input.files[0]);
}
else {
        console.log("Sorry - you're browser doesn't support the FileReader API");
    }
}

$uploadCrop = $('#upload-demo').croppie({
        viewport: {
                width: 200,
                height: 200,
                type: 'circle'
        },
        enableExif: true
});

$('#upload').on('change', function () { readFile(this); });

$('.upload-result').on('click', function (ev) {
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        // in this hidden input we can send cropped image to server
        $('#userprofile-avatar').val(resp);
        $('#cropped-result img').attr('src',resp);
        
        // Ora puoi inviarla con fetch
        fetch("upload_userpic.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ image: resp })
        });
    });
});

});
</script>

<form action="upload.php" method="post" enctype="multipart/form-data">
  Carica una foto:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Carica" name="submit">
</form>

<p>Città: <?php echo $user['city']; ?></p>

<!-- calcolo età -->
<?php
$birthday = strtotime($user['birthday']);
$now = time();
$datediff = $now - $birthday;
$age= intval($datediff*0.00000003170979);
?>

<?php echo '<p>Età: ',$age,'</p>';
echo 'Telegram: <a href="https://t.me/',$user['telegram'],'"/a>',$user['telegram'],'</a></p>'
?>



<p><a href="index.php">Homepage</a></p>
<p><a href="logout.php">Logout</a></p>

<form method="POST">
    <button type="submit">Cancellati</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
</body>
</html>