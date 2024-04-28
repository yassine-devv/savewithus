<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - SaveWithUs</title>

    <link rel="stylesheet" href="./style/registrazione.css">
    <link rel="stylesheet" href="./style/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <div class="navbar">
        <div class="img-logo">
            <a href="./index.php">
                SaveWithUs
            </a>
        </div>
        <div class="links">
            <a href="./index.php">Home</a>
            <a href="#">Campagne</a>
            <a href="#">Blog</a>
            <a href="#">Eventi</a>
            <a href="#">Login</a>
        </div>
    </div>

    <div class="container all-form-cite" style="margin: 0;">
        <div class="row form-cite form-cite-login">

            <div class="col sec-cite sec-cite-log">
                <p>
                    <cite>“Quando piantiamo un albero, <br> stiamo facendo ciò che possiamo <br> per rendere il nostro pianeta <br> un luogo più salutare e vivibile <br> per quelli che verranno dopo di noi, <br> se non per noi stessi.” <br> - OLIVER WENDELL HOLMES JR.</cite>
                </p>
            </div>

            <div class="col sez-form" style="padding: 4%;">
                <span class="title">Login</span>

                <form name="form-log" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="inputs-r inp-l">
                        <input type="text" placeholder="Username"> <br>
                        <input type="password" placeholder="Passowrd">
                    </div>

                    <br><br>

                    <span>Non hai un account? <a href="./registrazione.php">Registrati</a></span>

                    <br><br>
            
                    <button type="submit" class="btn btn-warning">Entra</button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>