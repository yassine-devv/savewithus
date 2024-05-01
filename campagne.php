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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>