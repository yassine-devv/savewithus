<?php
session_start();

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
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