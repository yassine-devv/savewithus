<?php
//session_unset();
//var_dump($_COOKIE);
session_start();
include("../db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $conn->real_escape_string(stripslashes($_POST['username']));
    $password = $conn->real_escape_string(stripslashes($_POST['password']));

    /* echo $_POST['ricordami']; */

    $sql = "SELECT * from amministratori WHERE amministratori.username='" . $username . "'";
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {

                $_SESSION['idamm'] = $row['id_admin'];
                //$_SESSION['username'] = $row['username'];

                header("location: index.php");
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
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./style/login.css">
</head>

<body>
    <div class="div-inps">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <img src="../imgs/logoswu.png" alt="Logo SaveWithUs" width="300">
            <br>
            <h3 align="center">Login Amministatore</h1>
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <?php
            if (isset($err)) {
                echo '<br><span style="color: red; font-weight: bold">' . $err . '</span><br>';
            }
            ?>
            <input class="btn btn-success" type="submit" value="Entra">
        </form>
    </div>
</body>

</html>