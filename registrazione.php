<?php
include("./db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $nome = $conn->real_escape_string(stripslashes($_POST['nome']));
    $cognome = $conn->real_escape_string(stripslashes($_POST['cognome']));
    $email = $conn->real_escape_string(stripslashes(strtolower($_POST['email'])));
    $tel = $conn->real_escape_string(stripslashes($_POST['tel']));
    $username = $conn->real_escape_string(stripslashes($_POST['username']));
    $password = $conn->real_escape_string(stripslashes($_POST['password']));

    $hashpass = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO `utenti`(`nome`, `cognome`, `email`, `num_tel`, `username`, `password`,  `created`) VALUES ('".$nome."','".$cognome."','".$email."','".$tel."','".$username."','".$hashpass."','".date("Y-m-d H:i:s")."')";

    //die($sql);
    if($conn->query($sql)){
        /*
        $id = $conn->insert_id; //prendo l'ultimo id dell'utente


        $id_token = uniqid();
        $id_token_hash = password_hash($id_token, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `cod_tokens`(`token`, `id_user`) VALUES ('".$id_token_hash."','".$id."')";*/

        //if($conn->query($sql)){
            header("location: login.php");
        //}
        
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - SaveWithUs</title>

    <link rel="stylesheet" href="./style/registrazione.css">
    <link rel="stylesheet" href="./style/style.css">

    <script src="./js/app.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <?php include "navbar.php" ?>

    <div class="container all-form-cite" style="margin: 0;">
        <div class="row form-cite form-cite-reg">
            
            <div class="col sec-cite sec-cite-reg">
                <p>
                    <cite>"O acqua! O aria! O terra! Terra morta…<br> tutte le stelle muoiono per collisione esterna, <br> esplosione o consumazione interna. <br> Questa sola muore perché i suoi abitanti <br> preferiscono alla sua vita limitata <br> l’illimitato della propria peccabilità." <br><br> - Guido Ceronetti</cite>
                </p>
            </div>

            <div class="col sez-form" style="padding: 4%;">
                <span class="title">Registrati</span>

                <form name="form-reg" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" onsubmit="return verify_reg()">
                    <div class="row inputs-r name-surname">
                        <div class="col">
                            <input type="text" placeholder="Nome" name="nome">
                        </div>
                        <div class="col">
                            <input type="text" placeholder="Cognome" name="cognome">
                        </div>
                    </div>

                    <div class="row inputs-r tel-email">
                        <div class="col-8">
                            <input type="email" placeholder="Email" name="email">
                        </div>
                        <div class="col-4">
                            <input type="number" placeholder="Numero" name="tel">
                        </div>
                    </div>

                    <div class="row inputs-r username-pass">
                        <div class="col">
                            <input type="text" placeholder="Username" name="username">
                        </div>
                        <div class="col">
                            <input type="password" id="inp-pass" placeholder="Password" name="password">
                        </div>
                        <div class="col-auto icon-viewpass">
                            <button id="btn-viewpass" type="button" onclick="viewpass()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" id="icon-eye" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                                    <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <br><br>
            
                    <button type="submit" class="btn btn-warning">Registrati</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>