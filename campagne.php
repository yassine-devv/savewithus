<?php
session_start();
include("./db.php");
include("./functions.php");
prepara_json();

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
}

//if (isset($_GET['id'])){

//}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campagne - SaveWithUs</title>

    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/campagne.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        tr {
            cursor: pointer;
        }
    </style>
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
            <a href="blogs.php">Blog</a>
            <a href="eventi.php">Eventi</a>
            <?php
            if (isset($_SESSION['iduser'])) {
                echo '<a href="user.php">Ciao, ' . $_SESSION['username'] . '</a>';
            } else {
                echo '<a href="login.php">Login</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['modifica-camp'])) {
                $nome_cmp = $_POST['nome-camp'];
                $desc_cmp = $_POST['descrzione-camp'];
                $data_cmp = $_POST['data-camp'];

                $sql = "UPDATE `campagne` SET nome_campagna='" . $nome_cmp . "', descrizione='" . $desc_cmp . "', giorno_ritrovo='" . $data_cmp . "' WHERE campagne.id_campagna=" . $id;
                //$result = $conn->query($sql);

                if ($result = $conn->query($sql)) {
                    echo '<script>alert("Modifica avvenuta con successo")</script>';
                }

                //header("location campagne.php?id=".$id);
            }
        }

        $sql = "SELECT utenti.id_user, utenti.username, campagne.id_campagna, campagne.nome_campagna, campagne.descrizione, campagne.giorno_ritrovo, campagne.foto, campagne.stato, campagne.autore, campagne.luogo, campagne.latitudine, campagne.longitudine FROM utenti join campagne on utenti.id_user=campagne.autore WHERE campagne.id_campagna=" . $id;
        $ris = $conn->query($sql);

        $data = [];

        $arrkeys = ['id_user', 'username', 'id_campagna', 'nome_campagna', 'descrizione', 'giorno_ritrovo', 'foto', 'stato', 'autore', 'luogo', 'latitudine', 'longitudine'];
        if ($ris->num_rows > 0) {
            while ($row = $ris->fetch_assoc()) {
                for ($i = 0; $i < count($arrkeys); $i++) {
                    $data[$arrkeys[$i]] = $row[$arrkeys[$i]];
                }
            }
        } else {
            $cmp_notfound = "La campagna che si desidera non esiste.";
        }

        echo '<script>var lat=' . $data['latitudine'] . '</script>';
        echo '<script>var lon=' . $data['longitudine'] . '</script>';
    ?>
        <div class="sec-banner">
            <div class="container banner">
                <div class="row">
                    <div class="col">
                        <span class="nome_cmp">Nome campagna: <?= $data['nome_campagna'] ?> </span> <br><br>
                        <span class="autore">Autore:
                            <?php
                            if ($data['id_user'] == $_SESSION['iduser']) {
                            ?>
                                <a href="./user.php"><?= $data['username'] ?></a>
                            <?php
                            } else {
                            ?>
                                <a href="./user.php?id=<?= $data['id_user'] ?>"><?= $data['username'] ?></a>
                            <?php
                            }
                            ?>
                        </span> <br><br>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>

        <div class="sec-main">
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        <div id="mapcmp"></div>
                    </div>
                    <div class="col-4">
                        <p><b>Descrizione area: </b><?= $data['descrizione'] ?></p>
                        <p><b>Luogo: </b><?= $data['luogo'] ?></p>
                        <p><b>Data: </b><?= $data['giorno_ritrovo'] ?></p>
                        <p><b>Stato: </b>
                            <?php
                            switch ($data['stato']) {
                                case '1':
                                    echo "In validazione";
                                    break;
                                case '2':
                                    echo "In attesa";
                                    break;
                                case '0':
                                    echo "In corso";
                                    break;
                            }
                            ?>
                        </p>

                        <?php
                        $sql = "SELECT campagne.id_campagna, campagne.autore FROM campagne WHERE campagne.id_campagna=" . $_GET['id'] . " and campagne.autore=" . $_SESSION['iduser'];
                        $ris = $conn->query($sql);

                        if ($ris->num_rows !== 1) {
                            $sql = "SELECT * FROM `partecipanti_camapgne` WHERE partecipanti_camapgne.id_user=" . $_SESSION['iduser'] . " and partecipanti_camapgne.id_campagna=" . $_GET['id'];
                            $ris = $conn->query($sql);

                            if ($ris->num_rows > 0) {
                        ?>
                                <button type="button" class="btn btn-danger" onclick="azioni_campagna('annulla', '<?= $_GET['id'] ?>')">
                                    Annulla iscrizione
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                    </svg>
                                </button>
                            <?php
                            } else {
                            ?>
                                <button id="iscr_cmp" type="button" class="btn btn-warning" onclick="azioni_campagna('iscrizione', '<?= $_GET['id'] ?>')">Iscriviti alla campagna</button>
                            <?php
                            }
                        } else {
                            ?>
                            <button type="button" class="btn btn-info" onclick="view_tab_mod()">Modifica campagna</button>
                        <?php
                        }
                        ?>


                    </div>
                </div>
            </div>

            <div class="sec-img">
                <div class="img-slide">
                    <button id="prec">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                            <path d="M15.293 3.293 6.586 12l8.707 8.707 1.414-1.414L9.414 12l7.293-7.293-1.414-1.414z" />
                        </svg>
                    </button>

                    <img alt="Slider" name="slide">

                    <button id="pross">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                            <path d="M7.293 4.707 14.586 12l-7.293 7.293 1.414 1.414L17.414 12 8.707 3.293 7.293 4.707z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="sec-tabs">
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Commenti')" id="defaultOpen">Commenti</button>
                    <button class="tablinks" onclick="openTab(event, 'Partecipanti')">Partecipanti</button>
                </div>

                <div id="Commenti" class="tabcontent">
                    <div class="addcomment">
                        <label>Pubblica un commento:</label><br>
                        <!-- <textarea name="commento" id="inp-commento"></textarea> -->
                        <input type="text" name="commento" id="inp-commento">
                        <button type="button" onclick="addcomment()" class="btn btn-primary">Pubblica</button><br><br>
                    </div>
                    <div id="content"></div>
                </div>
                <div id="Partecipanti" class="tabcontent"></div>

            </div>

        </div>
    <?php
        /* var_dump($data); */
    } else {
    ?>
        <div class="container map-tab">
            <div class="row">
                <div class="col map">
                    <div id="map"></div>
                </div>

                <div class="col view-tab">
                    <div class="row">
                        <div class="col-8">
                            <span id="title">Segnalazioni</span>
                        </div>
                        <div class="col-4">

                            <button onclick="window.location='segnala.php'" type="button" class="btn btn-warning">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                </svg>

                                Segnala
                            </button>

                        </div>
                    </div>

                    <div class="tab-camps">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Autore</th>
                                    <th scope="col">Nome campagna</th>
                                    <th scope="col">Stato</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = 'SELECT utenti.username, campagne.id_campagna, campagne.nome_campagna, campagne.stato, campagne.luogo, campagne.latitudine, campagne.longitudine FROM utenti join campagne on utenti.id_user=campagne.autore;';
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if ($row['stato']!=='1') {
                                ?>
                                            <tr onmouseenter="show_inmap(<?= $row['id_campagna'] ?>)" onclick="window.location='?id=<?= $row['id_campagna'] ?>'">
                                                <td><?= $row['username'] ?></td>
                                                <td><?= $row['nome_campagna'] ?></td>
                                                <td>
                                                    <?php
                                                    switch ($row['stato']) {
                                                        case '2':
                                                            echo 'In attesa';
                                                            break;
                                                        case '3':
                                                            echo 'Terminata';
                                                            break;
                                                        default:
                                                            echo $row['stato'];
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                <?php

                                        }
                                    }
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="box-modify-camp">
        <div class="top">
            <span id="title">MODIFICA CAMPAGNA</span><br>
            <svg onclick="close_box()" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
            </svg>
        </div>
        <span style="color: red">*Attenzione non è possibile modificare immagini e luogo della campagna*</span>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $id ?>" method="post">
            <div>
                <label>Nome campagna:</label>
                <input type="text" name="nome-camp" value="<?php echo $data['nome_campagna'] ?>">
                <label>Descrizone:</label>
                <textarea name="descrzione-camp"><?php echo $data['descrizione'] ?></textarea>
                <!-- <input type="text" value="<?php echo $data['descrizione'] ?>" > -->
                <label>Giorno ritrovo:</label>
                <input type="date" name="data-camp" value="<?php echo $data['giorno_ritrovo'] ?>">
            </div>
            <input type="submit" class="btn btn-primary" name="modifica-camp" value="Conferma">

        </form>
    </div>

    <!-- modal -->
    <div id="box-msg" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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

    <script src="./js/campagne.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>

</html>