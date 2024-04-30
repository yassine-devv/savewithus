<?php 
//session_unset();
session_start();
include("./db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $conn->real_escape_string(stripslashes($_POST['username']));
    $password = $conn->real_escape_string(stripslashes($_POST['password']));

    $sql = "SELECT * from utenti WHERE utenti.username='".$username."'";
    $ris = $conn->query($sql);

    if($ris->num_rows > 1){
        while($row = $ris->fetch_assoc()){
            if(password_verify($password, $row['password'])){
                //die("password corretta");

                //set cookie per ricordare l'user
                if(isset($_POST['ricordami'])){
                    $sqltok = "SELECT * from cod_tokens where cod_tokens.id_user=".$row['id_user'];
                    $ristok = $conn->query($sqltok);

                    if($ristok->num_rows > 0){
                        while($rowtok = $ristok->fetch_assoc()){
                            setcookie("remember_user", $rowtok['token'], time()+1296000); //cookie per 15 giorni
                        }
                    }
                }

                $_SESSION['iduser'] = $row['id_user'];
                $_SESSION['username'] = $row['username'];

                header("location: index.php");
            }else{
                $err = ' 2 Credenziali non corrette, riprova.'; 
            }
        }
    }else{
        $err = ' 1 Credenziali non corrette, riprova.'; 
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SaveWithUs</title>

    <link rel="stylesheet" href="./style/registrazione.css">
    <link rel="stylesheet" href="./style/style.css">
    <script src="./js/app.js"></script>

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

                <form name="form-log" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" onsubmit="return verify_login()">
                    <div class="inputs-r inp-l">
                        <input type="text" placeholder="Username" name="username"> <br>
                        <input type="password" placeholder="Passowrd" name="password">
                    </div>


                    <?php 
                    if(isset($err)){
                        echo '<span style="color: red; font-weight: bold">'.$err.'</span>';
                    }
                    ?>

                    <br>

                    <input type="checkbox" name="ricordami" value="ricordami">
                    <label>Ricordami</label>

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