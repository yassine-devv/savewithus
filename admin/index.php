<?php
session_start();
include ("../db.php");

if (!isset($_SESSION['idamm'])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amministratore</title>
    <link rel="stylesheet" href="./style/style.css">

</head>

<body>
    <div class="all">
        <div class="right-banner">
            <img src="../imgs/logoswu.png" alt="Logo SaveWithUs" width="300">
            <h1>Amministratore</h1>
            <div>
                <a class="link-ban" href="index.php">Dati profilo</a><br><br>
                <a class="link-ban" href="index.php?page=profili">Profili</a><br><br>
                <a class="link-ban" href="index.php?page=campagne">Campagne</a><br><br>
                <a class="link-ban" href="index.php?page=blog">Blog</a><br><br>
                <a class="link-ban" href="index.php?page=eventi">Eventi</a><br><br>
                <a class="link-ban" href="loggout.php">Esci</a>
            </div>
        </div>
        <div class="main">
            ciao
        </div>
    </div>


</body>

</html>