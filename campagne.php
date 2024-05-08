<?php
session_start();
include ("./db.php");
include ("./functions.php");
prepara_json();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campagne - SaveWithUs</title>

    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/campagne.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
            <a href="#">Campagne</a>
            <a href="#">Blog</a>
            <a href="#">Eventi</a>
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
                        <span class="autore">Autore: <a href="./profile.php?id=<?= $data['id_user'] ?>">
                                <?= $data['username'] ?></a> </span> <br><br>
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
                        <p><b>Stato: </b><?= $data['stato'] ?></p>
                        <?php
                        $sql = "SELECT * FROM `partecipanti_camapgne` WHERE partecipanti_camapgne.id_user=" . $_SESSION['iduser'] . " and partecipanti_camapgne.id_campagna=" . $_GET['id'];
                        $ris = $conn->query($sql);

                        if ($ris->num_rows > 0) {
                            ?>
                            <button type="button" class="btn btn-danger" onclick="azioni_campagna('annulla', '<?= $_GET['id'] ?>')">
                                Annulla iscrizione
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                    class="bi bi-x" viewBox="0 0 16 16">
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </button>
                            <?php
                        } else {
                            ?>
                            <button id="iscr_cmp" type="button" class="btn btn-warning"
                                onclick="azioni_campagna('iscrizione', '<?= $_GET['id'] ?>')">Iscriviti alla campagna</button>
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

            <!--
            <?php
            $arrimgspath = explode(",", $data['foto']);
            array_pop($arrimgspath);

            foreach ($arrimgspath as $img) {
                echo "./uploads/campagne/" . $data['id_campagna'] . "/" . $img . " ";

                echo '<script>addPathImg("./uploads/campagne/' . $data['id_campagna'] . '/' . $img . '")</script>';

                //echo '<img style="with: 300px; height: 300px" src="./uploads/campagne/' . $data['id_campagna'] . '/' . $img . '" alt="">';
            }
            ?> -->


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

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
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
                                        ?>
                                        <tr onmouseenter="show_inmap(<?= $row['id_campagna'] ?>)"
                                            onclick="window.location='?id=<?= $row['id_campagna'] ?>'">
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




    <footer>
        <div class="container">
            <div class="row">
                <div class="col info-contact">
                    <img src="./imgs/logoswu.png" alt="" width="300" height="100"><br>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-envelope" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                        </svg>
                        <span>info@savewithus.com</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
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

    <script>
        var pathimgs = [];
        function addPathImg(pathimg) {
            console.log(pathimg);
            pathimgs.push(pathimg);
        }

        function getPathImgs(arr) {
            return arr;
        }

        console.log(pathimgs);

        console.log(document.body.contains(document.getElementById("mapcmp")))

        if (document.body.contains(document.getElementById("map"))) {
            var map = L.map('map').setView([41.459, 12.700], 5);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            const req = new Request("data.json");

            fetch(req)
                .then((response) => response.json())
                .then((data) => {
                    console.log(data[0]['campagna']['id']);

                    for (var i = 0; i < data.length; i++) {
                        var marker = L.marker([data[i]['campagna']['lat'], data[i]['campagna']['lon']]).addTo(map);
                        //marker.bindPopup("<b>" + data[i]['campagna']['nome_campagna'] + "</b>").openPopup();
                    }
                })
                .catch(console.error);


            function show_inmap(id) {
                fetch(req)
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data[0]['campagna']['id']);
                        for (var i = 0; i < data.length; i++) {
                            if (data[i]['campagna']['id'] == id) {
                                var popup = L.popup()
                                    .setLatLng([data[i]['campagna']['lat'], data[i]['campagna']['lon']])
                                    .setContent(data[i]['campagna']['nome_campagna'])
                                    .openOn(map);
                            }
                        }
                    })
                    .catch(console.error);
            }
        } else {
            //carousel
            /*SLIDESHOW*/
            let i = 0; //indice iniziale
            let img = []; //array di immagini


            var url_string = window.location.href;
            var url = new URL(url_string);
            var c = url.searchParams.get("id");
            console.log(c);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let data = JSON.parse(this.response);
                    console.log(data[0]['campagna']['lat']);

                    var map = L.map('mapcmp').setView([data[0]['campagna']['lat'], data[0]['campagna']['lon']], 5);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    var marker = L.marker([data[0]['campagna']['lat'], data[0]['campagna']['lon']]).addTo(map);

                    let arrimgs = data[0]['campagna']['foto'].split(",");
                    console.log(arrimgs);
                    arrimgs.pop();

                    for (let j = 0; j < arrimgs.length; j++) {
                        console.log("./uploads/campagne/" + data[0]['campagna']['id'] + "/" + arrimgs[j]);
                        img[j] = "./uploads/campagne/" + data[0]['campagna']['id'] + "/" + arrimgs[j];
                    }

                    //lista di immagini
                    //img[0] = "./imgs/calcio_campo.jpg";
                    //img[1] = "./imgs/pallavolo_campo.jpg";
                    //img[2] = "./imgs/tennis_campo.jpg";
                    document.slide.src = img[i];

                    let btnPross = document.getElementById("pross");
                    btnPross.addEventListener("click", prossImg);

                    let btnPrec = document.getElementById("prec");
                    btnPrec.addEventListener("click", precImg);
                    //console.log(arrimgs.length);
                }
            }
            xmlhttp.open("GET", "functions.php?id_cmp=" + c, true);
            xmlhttp.send();

            //console.log("1 "+getPathImgs());

            function prossImg() {
                //percorre tutte le immagini
                if (i < img.length - 1) {
                    i++;
                } else {
                    i = 0;
                }

                document.slide.src = img[i];
            }

            function precImg() {
                //percorre tutte le immagini
                if (i == 0) {
                    i = img.length - 1;
                } else {
                    i--;
                }

                document.slide.src = img[i];
            }

            function azioni_campagna(azione, id){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        let data = JSON.parse(this.response);

                        if(azione == 'iscrizione'){
                            console.log(data);
    
                            if (data['result'] == true) {
                                alert(data['msg']);
                                let oldbtn = document.querySelector(".col-4 .btn-warning");
                                oldbtn.remove();
        
                                let btniscr = '<button type="button" class="btn btn-danger" onclick="azioni_campagna(\'annulla\', '+c+')">Annulla iscrizione<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" /></svg></button>';
                                document.querySelector(".col-4").innerHTML += btniscr;
                            }
                            
                            if (data['result'] == false) {
                                alert(data['msg']);
                            }
                            if (data['result'] == "0") {
                                location.replace("login.php");
                            }
                        }
                        
                        if(azione=="annulla"){
                            if (data['result'] == true) {
                                alert(data['msg']);
                                let oldbtn = document.querySelector(".col-4 .btn-danger");
                                oldbtn.remove();

                                let btniscr = '<button id="iscr_cmp" type="button" class="btn btn-warning" onclick="azioni_campagna(\'iscrizione\', '+c+')">Iscriviti alla campagna</button>';
                                document.querySelector(".col-4").innerHTML += btniscr;

                            }
                        }

                    }

                }
                xmlhttp.open("GET", "functions.php?"+azione+"=" + id, true);
                xmlhttp.send();
            }

        }


    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>


</body>

</html>