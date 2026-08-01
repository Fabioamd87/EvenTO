<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvenTO</title>

    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"
        integrity="sha256-gvEnj2axkqIj4wbYhPjbWV7zttgpzBVEgHub9AAZQD4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="main.css">
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
        <h1>Portale per pubblicare eventi</h1>
        <p>Quest'app aiuta nella gestione dei partecipanti ad un evento</p>

        <h2>Auto</h2>
        <p>In particolare, tramite questo portale è possibile partecipare ad un evento, che richiede un trasferimento in auto,
            specificando se ci si unisce con la propia auto, indicando i posti liberi, o se si ha bisogno di un passaggio</p>
    </div>

    <footer>
        Footer
    </footer>

    <script>
        /*
            THIS IS NOT NECESSARY ON REGULAR WEBSITES
 
            If you're using a library like React, you'll need the mobile menu to close once a link has been clicked.
            That's all the below code does.
        */

        const hamburgerButton = document.getElementById('hamburger-button');
        const headerLinks = document.querySelectorAll('header .container .menu a');

        headerLinks.forEach((link) => {
            link.addEventListener('click', (e) => hamburgerButton.checked = false);
        })
    </script>
</body>

</html>