<?php
session_start();
include("./functions.php");
include("./db.php");
include("./ricordami_cookie.php");

if(!isset($_SESSION['iduser'])){
    header('location: login.php');
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST["pubb-blog"])){
        $titolo = addcslashes($_POST["title-blog"], "'");
        $content = addcslashes($_POST["descrizione"], "'");

        $sql = "INSERT INTO `blog`(`titolo`, `testo`, `created`, `stato`, `autore`) VALUES ('".$titolo."','".$content."','".date("Y-m-d")."','1','".$_SESSION['iduser']."')";
        //die($sql);
        //$result = $conn->query($sql);

        if($conn->query($sql)){
            header("location: blogs.php");
        }
    }
    
    if(isset($_POST['mod-blog'])){
        $sql = "UPDATE blog SET blog.stato='1', blog.titolo='".addcslashes($_POST['title-blog'], "'")."', blog.testo='".addslashes($_POST['descrizione'])."' WHERE blog.id_blog=".$_GET['id'];
        if($conn->query($sql)){
            header("location: user.php?page=blog");
        }
    }
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
    <link rel="stylesheet" href="./style/newblog.css">
    
    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>
    <?php include "navbar.php" ?>

    <?php if(!isset($_GET['id'])){?>
    <div class="main-blog">
        <div class="card-center">
            <h4>Nuovo Blog</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <label>Titolo del blog:</label>
                <input type="text" class="input-title" placeholder="Dai un titolo al tuo blog" name="title-blog">
                <label>Contenuto: </label>
                <textarea name="descrizione" rows="10" id="textarea-content" placeholder="Scrivi qua il contenuto del tuo blog"></textarea>
                <input type="submit" class="btn btn-primary pubb-blog" name="pubb-blog" value="Pubblica">
            </form>
        </div>
    </div>
    <?php }else{
        $sql = "SELECT blog.autore, blog.id_blog, blog.titolo, blog.testo FROM blog WHERE blog.id_blog=".$_GET['id'];
        $ris = $conn->query($sql);
        
        if($ris->num_rows == 1){
            $data = $ris->fetch_assoc();
            if($_SESSION['iduser']!==$data['autore']){
                header("location: user.php?page=blog");
            }
        }else{
            header("location: user.php?page=blog");
        }
        ?>
        <div class="main-blog">
        <div class="card-center">
            <h4>Modifica Blog</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$_GET['id'] ?>" method="post">
                <label>Titolo del blog:</label>
                <input type="text" class="input-title" placeholder="Dai un titolo al tuo blog" name="title-blog" value="<?= $data['titolo']?>">
                <label>Contenuto: </label>
                <textarea name="descrizione" rows="10" id="textarea-content" placeholder="Scrivi qua il contenuto del tuo blog"><?= $data['testo']?></textarea>
                <input type="submit" class="btn btn-primary pubb-blog" name="mod-blog" value="Modifica">
            </form>
        </div>
    </div>
    <?php } ?>



    <?php include "footer.php" ?>


</body>

</html>