<?php
session_start();
include ("./db.php");

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
}

$sql = "SELECT * FROM utenti WHERE utenti.id_user=" . $_SESSION['iduser'];
$ris = $conn->query($sql);
$data = [];

if ($ris->num_rows == 1) {
    $data = $ris->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style/user.css">
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>

    <!-- <?php
    echo "Ciao " . $_SESSION['username'];

    ?> -->
    <div class="right-banner">
        <h1>Profilo</h1>
        <a href="user.php">Dati profilo</a>
        <a href="page=blog">Blog</a>
        <a href="page=eventi">Eventi</a>
        <a href="page=campagne">Campagne</a>
    </div>

</body>

</html>