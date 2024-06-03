<?php
session_start();
include ("./db.php");
include ("./functions.php");
include("./ricordami_cookie.php");

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['agg-dati'])) {
        $sql = "UPDATE `utenti` SET `nome`='" . $_POST['nome'] . "',`cognome`='" . $_POST['cognome'] . "',`email`='" . $_POST['email'] . "',`num_tel`='" . $_POST['tel'] . "',`username`='" . $_POST['username'] . "' WHERE utenti.id_user='" . $_SESSION['iduser'] . "'";
        if ($conn->query($sql)) {
            ?>
            <script>alert('Dati aggiornati con successo!')</script>
            <?php
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - SaveWithUs</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/user.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <?php include ('./navbar.php') ?>
    
    <div class="all">
        <div class="right-banner">
            <h1>Profilo</h1>
            <div class="div-links">
                <a href="user.php">Dati profilo</a>
                <a href="?page=blog">Blog</a>
                <a href="?page=campagne">Campagne</a>
            </div>
        </div>

        <div class="left-part">
            <?php

            if (isset($_GET['page'])) {
                get_page_user($_GET['page']);
            } else {
                get_page_user('dati_profilo');
            }

            ?>
        </div>

    </div>


</body>

</html>