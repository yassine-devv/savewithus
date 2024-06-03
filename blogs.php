<?php
session_start();
include ("./functions.php");
include ("./db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - SaveWithUs</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/blog.css">

    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <?php include "navbar.php" ?>

    <div class="main-blog">
        <div class="top-title-btn">
            <h2>Blogs</h2>
            <button onclick="window.location.href = 'new_blog.php' " class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-patch-plus-fill" viewBox="0 0 16 16">
                    <path
                        d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0" />
                </svg>

                Pubblica blog
            </button>
        </div>
        <div class="container-blogs">
            <?php
            $sql = 'SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.stato, blog.autore, utenti.username FROM utenti join blog on utenti.id_user=blog.autore where blog.stato=2;';
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <img class="card-img-top" src="./imgs/img-bg.jpeg" alt="Card image cap"><br><br>
                            <h5 class="card-title"><?= $row['titolo'] ?></h5>
                            <p class="card-text"><b><?= $row['username'] ?></b></p>
                            <p class="card-text">Pubblicato il:<?= date('d M Y', strtotime($row['created']));?></p>
                            <a href="blog.php?id=<?= $row['id_blog'] ?>" class="btn btn-primary">Leggi</a>
                        </div>
                    </div>
                    <?php
                }

            } else {
                echo '<h4>Nessun blog pubblicato</h4>';
            }

            ?>
        </div> 
    </div>

    <?php include "footer.php" ?>
</body>

</html>