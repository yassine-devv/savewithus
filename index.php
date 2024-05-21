<?php 
session_start();
//echo $_COOKIE['remember_user'];
//echo $_SESSION['iduser'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <title>Home - SaveWithUs</title>

    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
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
            <a href="campagne.php">Campagne</a>
            <a href="blog.php">Blog</a>
            <a href="eventi.php">Eventi</a>
            <?php 
            if(isset($_SESSION['iduser'])){
                echo '<a href="user.php">Ciao, '.$_SESSION['username'].'</a>';
            }else{
                echo '<a href="login.php">Login</a>';
            }
            ?>
        </div>
    </div>

    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col">
                    <span class="citazione"><cite>"Prenditi cura della Terra e la Terra si prenderà cura di te, distruggi la Terra e la Terra ti distruggerà."</cite> </span> <br><br>
                    <button type="button" class="btn btn-primary btn-lg btn-banner">
                        <?php
                            if(!isset($_SESSION['iduser'])){
                                echo '<a href="login.php">Accedi</a>';
                            }else{
                                echo '<a href="user.php">Profilo</a>';
                            }
                        ?>
                    </button>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>

    <div class="sec-main">
        <p>
            <cite><b>Perchè questa iniziativa?</b></cite> <br><br>
            Questa iniziativa è nata con l'obiettivo di unire volontari e multinazionali italiane per affrontare la sfida delle aree inquinate e degradate che minacciano l'ambiente e la vita selvatica in giro per l'Italia. Lavorando insieme, possiamo ripulire queste aree e ripristinarle in spazi vitali sani e sostenibili per le generazioni presenti e future.
        </p>

        <div class="sec-how">
            <b><span>Come fare?</span></b> <br><br>
            <p><cite>Segnala l'area da ripulire in 3 semplici passi</cite></p><br><br>

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                    <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0" />
                                </svg><br><br>
                                <h5 class="card-title">Fotografa l'area</h5>
                                <p>Avvistata l'area da ripulire, scatta qualche foto e pubblicala con la campagna!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                </svg><br><br>
                                <h5 class="card-title">Segnala l'area</h5>
                                <p>Scattate le foto, segnala l'area e uno dei nostri collaboratori valuterà la campagna</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                                    <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z" />
                                </svg><br><br>
                                <h5 class="card-title">Segnalazione pubblicata</h5>
                                <p>Una volta valutata la segnalazione, la campagna verr&aacute; pubblicata online</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="sec-button-lancia">
        <span><cite>Segnala anche tu un'area da ripulire insieme!</cite></span><br><br>
        <button type="button" class="btn btn-success btn-sm">
            Segnala
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
        </button>

    </div>


    <footer>
        <div class="container">
            <div class="row">
                <div class="col info-contact">
                    <img src="./imgs/logoswu.png" alt="" width="300" height="100"><br>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                        </svg>
                        <span>info@savewithus.com</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                        </svg>

                        <span>+39 1234567890</span>
                    </div>
                </div>
                <div class="col links">
                    <a href="./index.php">Home</a>
                    <a href="#">Campagne</a>
                    <a href="#">Blog</a>
                    <a href="#">Eventi</a>
                    <a href="#">Login</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>