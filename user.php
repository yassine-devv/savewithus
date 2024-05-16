<?php
include ("./db.php");
session_start();

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
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/user.css">
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

    <div class="nav-right">
        <!--<b><a href="/">Social Yassine</a></b>-->
        <b>
            <h1>Profilo</h1>
        </b>

        <div class="links-profile">

            <a href="?page=profile">Dati profilo</a>
            <a href="?page=campagne">Campagne</a>
            <a href="?page=blog">Blog</a>

            <!-- <?php
            foreach ($pagesget as $key => $value) {
                $link = "<div>";
                $link .= $value['icon'];
                if ($_GET['page'] == $key) {
                    $link .= '<a class="active" href="profile.php?page=' . $key . '">' . $value['label'] . '</a>';
                } else {
                    $link .= '<a href="profile.php?page=' . $key . '">' . $value['label'] . '</a>';
                }
                $link .= "</div>";
                echo $link;
            }
            ?> -->
        </div>

        <button class="btn-loggout">
            <a href="./loggout.php">Esci</a>
        </button>
    </div>

</body>

</html>