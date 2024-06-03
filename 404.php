<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/404.css">
    <title>Not Found - SaveWithUs</title>

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

    <div class="msg">
        <div id="inter">
            <img src="./imgs/404.png" alt="" width="400" height="250">
            <div class="inter2">
                <p>404 <br> OPS! PAGINA <br> NON TROVATA.</p><br>
                <button class="btn btn-primary" onclick="window.location.href = 'index.php' ">Torna alla home</button>
            </div>
        </div>
    </div>

    <?php include "footer.php" ?>
</body>

</html>