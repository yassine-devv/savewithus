<?php
session_start();
include("./functions.php");
include("./db.php");

if(!isset($_GET["id"])){
    header("location: blogs.php");
}
$idblog = $_GET["id"];

$sql = "SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.stato, blog.autore, utenti.id_user, utenti.username FROM utenti join blog on utenti.id_user=blog.autore WHERE blog.id_blog=".$idblog;
$ris = $conn->query($sql);

if($ris->num_rows > 0){
    $row = $ris->fetch_assoc();
}else{
    header("location: blogs.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - SaveWithUs</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/blog.css">
    <link rel="stylesheet" href="./style/blogid.css">

    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>

    <?php include "navbar.php" ?>

    <div class="banner">
        <p class="title"><?= $row['titolo'] ?></p>
        <p class="autore">Autore: <?= $row['username'] ?></p>
        <?php 
        if(isset($_SESSION['iduser'])) {
            if($row['autore'] == $_SESSION['iduser']){
                ?>
                <button onclick="window.location.href = 'user.php?page=blog'" style="color: white" class="btn btn-info">Modifica</button>
                <?php
            }
        }
        ?>
    </div>

    <div class="content-txt">
        <p class="content"><?= $row['testo']?></p><br><br>
        <p >
            Pubblicato il: <?= $row['created']?>   
        </p>
    </div>

    <?php include "footer.php" ?>
</body>

</html>