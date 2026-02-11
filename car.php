<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventi</title>
</head>
<body>

<?php
    include 'db.php';
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        #SANITIZE
        $event_id = $_GET["id"];
        $_SESSION["event_id"] = $event_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['join'] == 1){
            //Need a car mode
            $event_id = $_SESSION["event_id"];
            $user_id = $_SESSION["user_id"];
            
            #get all participands to the event
            $stmt = $pdo->prepare(
                "SELECT id,name,with_car,seats
                FROM users
                JOIN partecipations ON users.id = partecipations.user_id
                WHERE partecipations.event_id = ?");
            $stmt->execute([$event_id]);
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);  
            
            #assigning free seats
            if ($participants){
                foreach ($participants as $participant){
                    if ($participant['seats']>0){
                        #insert the user in the first free seat
                        $stmt = $pdo->prepare("INSERT INTO partecipations (
                            user_id,event_id,with_car,car_id
                            ) VALUES (?, ?, ?, ?)");
                        try {
                            $stmt->execute([$user_id,$event_id,'false',$participant['id']]);
                            header('Location: event.php?id='. $event_id);
                            exit();                            
                        } catch (PDOException $e) {
                            $error = 'Errore.';
                        }
                    }
                    else echo "Nessun posto disponibile nell'auto di ",$participant['name'];
                }
            }
            else{
                #insert the user in waiting list
                $stmt = $pdo->prepare("INSERT INTO partecipations (
                    user_id,event_id,with_car
                    ) VALUES (?, ?, ?)");
                try {
                    $stmt->execute([$user_id, $event_id, 'false']);
                    header('Location: event.php?id='. $event_id);
                    exit();                            
                } catch (PDOException $e) {
                    $error = 'Errore.';
                }
            }
        }
        if ($_POST['join'] == 2){

            #check if the owner cars is already existing
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$_SESSION['user']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            #SANITIZE
            $seats = $_POST['seats'];
            $event_id = $_POST["event_id"];

            $stmt = $pdo->prepare("INSERT INTO partecipations (
            user_id,event_id,with_car,seats,car_id
            ) VALUES (?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$user['id'],$event_id,'true',$seats,$user['id']]);
                header('Location: event.php?id='. $event_id);
                exit();
            } catch (PDOException $e) {
                $error = 'Errore.';
            }
        }

    }
?>

<form id="selection" method="POST">
<select id="join" name="join">
  <option value="1">Ho bisogno di un passaggio</option>
  <option value="2">Vengo in auto</option>
</select>
<input type="hidden" name="event_id" value="<?php echo $event_id;?>">
<input type="submit" value="Partecipa" id="input_button"></button>

<script type="text/javascript">
var qSelect = document.getElementById("join");
function clck(){
    if(qSelect.value == "1"){
        //todo
    }
	if(qSelect.value == "2"){
        const inputbutton = document.getElementById("input_button");
        inputbutton.remove();
        
        const input = document.createElement("input");
        input.setAttribute("id", "auto");
        input.setAttribute("name", "seats");
        input.setAttribute("placeholder", "Posti Auto");
		const element = document.getElementById("selection");
        element.appendChild(input);

        const submit_button = document.createElement("input");
        submit_button.setAttribute("type", "submit");
        submit_button.setAttribute("value", "Partecipa");
        submit_button.setAttribute("id", "input_button");
		const selection = document.getElementById("selection");
        selection.appendChild(submit_button);        

	}else{	
        const element = document.getElementById("auto");
        element.remove();
    }
	if(qSelect.value == "3"){
        //todo
    }
}
qSelect.addEventListener('change',clck);
</script>
</form>