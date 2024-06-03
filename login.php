<?php
//session_unset();
//var_dump($_COOKIE);
session_start();
include ("./db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $conn->real_escape_string(stripslashes($_POST['username']));
    $password = $conn->real_escape_string(stripslashes($_POST['password']));

    /* echo $_POST['ricordami']; */

    $sql = "SELECT * from utenti WHERE utenti.username='" . $username . "'";
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                if (isset($_POST['ricordami'])) {
                    $token = bin2hex(random_bytes(16)); // funzione bin2hex converte una stringa binaria in esadecimale. random_bytes genera una string con lunghezza indicata
                    $scadenza = time() + (86400 * 15); // 15 giorni

                    setcookie('ricordami', $token, $scadenza, "/", "", true, true);

                    $sqltk = "INSERT INTO tokens_utenti (id_user, token, scandenza) VALUES ('".$row['id_user']."', '".$token."', '".date('Y-m-d H:i:s', $scadenza)."')";
                    $ristk = $conn->query($sqltk); 
                }

                $_SESSION['iduser'] = $row['id_user'];
                $_SESSION['username'] = $row['username'];

                header("location: user.php");
                exit(0);
            } else {
                $err = 'Credenziali non corrette, riprova.';
            }
        }
    } else {
        $err = 'Credenziali non corrette, riprova.';
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <?php include "navbar.php" ?>

    <div class="container all-form-cite" style="margin: 0;">
        <div class="row form-cite form-cite-login">

            <div class="col sec-cite sec-cite-log">
                <p>
                    <cite>“Quando piantiamo un albero, <br> stiamo facendo ciò che possiamo <br> per rendere il nostro
                        pianeta <br> un luogo più salutare e vivibile <br> per quelli che verranno dopo di noi, <br> se
                        non per noi stessi.” <br> - OLIVER WENDELL HOLMES JR.</cite>
                </p>
            </div>

            <div class="col sez-form" style="padding: 4%;">
                <span class="title">Login</span>

                <form name="form-log" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post"
                    onsubmit="return verify_login()">
                    <div class="container inputs-r inp-l">
                        <div class="row">
                            <div class="col">
                                <input type="text" placeholder="Username" name="username">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="password" id="inp-pass" placeholder="Passowrd" name="password">
                            </div>
                            <div class="col-auto icon-viewpass">
                                <button id="btn-viewpass" type="button" onclick="viewpass()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        id="icon-eye" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                                        <path
                                            d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>

                    <?php
                    if (isset($err)) {
                        echo '<span style="color: red; font-weight: bold">' . $err . '</span>';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>