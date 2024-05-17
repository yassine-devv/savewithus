<?php
session_start();
include ("../db.php");
include ("./pages.php");


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


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
                <a class="link-ban" href="loggut.php">Esci</a>
            </div>
        </div>
        <div class="main">
            <?php 
            if(isset($_GET['page'])){
                get_page($_GET['page']);
            }else{
                get_page('dati_profilo');
            }
            ?>
        </div>
    </div>


</body>

</html>