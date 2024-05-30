<?php
session_start();
include ("./functions.php");
include ("./db.php");

if (!isset($_SESSION["iduser"])) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome_cmp = $conn->real_escape_string(stripslashes($_POST["nome-campagna"]));
    $desc_cmp = $conn->real_escape_string(stripslashes($_POST["descrizione-camp"]));
    $lat = $_POST["lat"];
    $lon = $_POST["lon"];
    $dsp_nm = $_POST["dsp_nm"];
    $data_cmp = $_POST["data-svolg"];

    /*file*/
    $rismovfile = false;
    // configura il path della cartella in cui verranno messe le foto
    $upload_dir = 'uploads\campagne' . DIRECTORY_SEPARATOR;
    $allowed_types = array('jpg', 'png', 'jpeg', 'gif'); // array delle estensioni ammesse
    $arr_imgs = [];

    // grandezza massima delel immiagini 2MB
    $maxsize = 2 * 1024 * 1024;

    foreach ($_FILES['files']['tmp_name'] as $key => $value) {

        $file_tmpname = $_FILES['files']['tmp_name'][$key]; // prende la posizione temporanea del file
        $file_name = $_FILES['files']['name'][$key]; //nome del file
        $file_size = $_FILES['files']['size'][$key]; //grandezza del file
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION); //estensione del file

        // prepara il path della cartella con l'immagine
        $filepath = $upload_dir . $file_name;

        // controlla sell'estensione del file è possibile
        if (in_array(strtolower($file_ext), $allowed_types)) {

            // verifica la grandezza del file
            if ($file_size > $maxsize)
                echo "Error: Grandezza del file troppo grande";

            $rismovfile = true;
            array_push($arr_imgs, $file_name);

        } else {

            // If file extension not valid
            echo "Error uploading {$file_name} ";
            echo "({$file_ext} file type is not allowed)<br / >";
        }
    }

    //prepara la la lista sotto forma di stringa con tutte le foto per memorizzarla nel db
    $str_imgs_path = "";
    for ($i = 0; $i < count($arr_imgs); $i++) {
        $str_imgs_path .= $arr_imgs[$i] . ",";
    }

    //echo $str_imgs_path;

    $sql = "INSERT INTO `campagne`(`nome_campagna`, `descrizione`, `giorno_ritrovo`, `foto`, `stato`, `autore`, `luogo`, `latitudine`, `longitudine`) VALUES ('" . $nome_cmp . "','" . $desc_cmp . "','" . $data_cmp . "','" . $str_imgs_path . "','1','" . $_SESSION['iduser'] . "','" . $dsp_nm . "','" . $lat . "','" . $lon . "')";
    $result = $conn->query($sql);

    if ($result) {
        $last_id = $conn->insert_id; // id dell'ultimo elemento inserito

        $path_dir_id = 'uploads/campagne/' . $last_id;
        //se la cartella con nome id della campagna non esiste la crea
        if (!file_exists($path_dir_id)) {
            mkdir($path_dir_id, 0777, true);
        }

        if ($rismovfile) {
            foreach ($_FILES['files']['tmp_name'] as $key => $value) {
                $file_tmpname = $_FILES['files']['tmp_name'][$key];
                $filepath = $path_dir_id . DIRECTORY_SEPARATOR . $_FILES['files']['name'][$key];
                ;
                move_uploaded_file($file_tmpname, $filepath);
            }
        }

        header("location: campagne.php");
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segnala</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/segnala.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

    <style>
        /*
        #map {
            height: 300px;
        }
        
        #results{
            /* position: absolute; */
        /* top: 0;
            left: 0; 
            border: black 1px solid;
            border: black 1px solid;
        }
        */

        #lat,
        #lon,
        #dsp_nm {
            display: none;
        }

        #results {
            overflow-y: scroll;
            height: 100px;
            display: none;
        }

        #results p {
            margin: 0;
        }

        .container {
            width: 95%;
            max-width: 980px;
            padding: 1% 2%;
            margin: 0 auto
        }

        #map {
            /* width: 100%;
            height: 50%; */
            height: 300px;

            padding: 0;
            margin: 0;
            z-index: 1;
        }

        .address {
            cursor: pointer
        }

        .address:hover {
            color: #AA0000;
            text-decoration: underline
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
            if(isset($_SESSION['iduser'])){
                echo '<a href="user.php">Ciao, '.$_SESSION['username'].'</a>';
            }else{
                echo '<a href="login.php">Login</a>';
            }
            ?>
        </div>
    </div>



    <div class="formbold-main-wrapper">
        <div class="formbold-form-wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                enctype="multipart/form-data">
                <div class="formbold-steps">
                    <ul>
                        <li class="formbold-step-menu1 active">
                            <span>1</span>
                            Nome Campagna
                        </li>
                        <li class="formbold-step-menu2">
                            <span>2</span>
                            Foto
                        </li>
                        <li class="formbold-step-menu3">
                            <span>3</span>
                            Luogo
                        </li>
                    </ul>
                </div>

                <div class="formbold-form-step-1 active">
                    <div>
                        <label for="address" class="formbold-form-label">Nome campanga</label>
                        <input type="text" name="nome-campagna" id="address" placeholder="Dai un nome alla tua campgna"
                            class="formbold-form-input" />
                    </div>

                    <div>
                        <label for="address" class="formbold-form-label">Descrizione</label>
                        <textarea name="descrizione-camp" cols="65" rows="7"
                            placeholder="Descrivi un po l'area"></textarea>
                    </div>
                    <div>
                        <label for="date" class="formbold-form-label">Data:</label>
                        <input type="date" name="data-svolg" id="">
                    </div>
                </div>

                <div class="formbold-form-step-2">
                    <label>Scegli delle foto che rappresentano l'area che vuoi segnalare:</label><br>
                    <input type="file" name="files[]" multiple>
                </div>

                <div class="formbold-form-step-3 ">
                    <div class="formbold-form-confirm">
                        <div class="container" style="align-content: center">

                            <!-- <b>Coordinates</b> -->

                            <!-- <form> -->
                            <input type="text" name="lat" id="lat" size=12 value="">
                            <input type="text" name="lon" id="lon" size=12 value="">
                            <input type="text" name="dsp_nm" id="dsp_nm" value="">
                            <!-- </form> -->

                            <label for="address" class="formbold-form-label">Inserisci il luogo:</label>

                            <div class="row">
                                <div class="col-8">
                                    <input type="text" name="addr" value="" id="addr" size="58"
                                        class="formbold-form-input" />
                                    <div id="results"></div>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-primary" onclick="addr_search();"
                                        class="">Search</button>
                                </div>
                            </div>
                            <br />
                            <div id="map"></div>

                        </div>

                    </div>
                </div>

                <div class="formbold-form-btn-wrapper">
                    <button class="formbold-back-btn">
                        Inditro
                    </button>

                    <button class="formbold-btn">
                        Avanti
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1675_1807)">
                                <path
                                    d="M10.7814 7.33312L7.20541 3.75712L8.14808 2.81445L13.3334 7.99979L8.14808 13.1851L7.20541 12.2425L10.7814 8.66645H2.66675V7.33312H10.7814Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1675_1807">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 41.458,12.706
        var startlat = 41.458;
        var startlon = 12.706;

        var options = {
            center: [startlat, startlon],
            zoom: 12
        }

        //document.getElementById('lat').value = startlat;
        //document.getElementById('lon').value = startlon;

        var map = L.map('map', options);
        var nzoom = 12;

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: 'OSM'
        }).addTo(map);

        var myMarker = L.marker([startlat, startlon], {
            title: "Coordinates",
            alt: "Coordinates",
            draggable: true
        }).addTo(map).on('dragend', function () {
            var lat = myMarker.getLatLng().lat.toFixed(8);
            var lon = myMarker.getLatLng().lng.toFixed(8);
            var czoom = map.getZoom();
            if (czoom < 18) {
                nzoom = czoom + 2;
            }
            if (nzoom > 18) {
                nzoom = 18;
            }
            if (czoom != 18) {
                map.setView([lat, lon], nzoom);
            } else {
                map.setView([lat, lon]);
            }
            document.getElementById('lat').value = lat;
            document.getElementById('lon').value = lon;
            myMarker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
        });

        //https://nominatim.openstreetmap.org/reverse?format=json&lat=45.4640818899535&lon=9.189596264423086&zoom=18&addressdetails=1 prende json con tutti dati e nome della posizione

        function chooseAddr(lat1, lng1, dsp_name) {
            myMarker.closePopup();
            map.setView([lat1, lng1], 18);
            myMarker.setLatLng([lat1, lng1]);
            lat = lat1.toFixed(8);
            lon = lng1.toFixed(8);

            document.getElementById('lat').value = lat;
            document.getElementById('lon').value = lon;
            document.getElementById('dsp_nm').value = dsp_name;
            console.log(dsp_name);
            console.log(lat);
            console.log(lon);
            myMarker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
        }

        let marker = null;
        map.on('click', (event) => {
            if (marker !== null) {
                map.removeLayer(marker);
            }
            marker = L.marker([event.latlng.lat, event.latlng.lng]).addTo(map);
            console.log(event.latlng.lat+" "+event.latlng.lng);
        })

        function myFunction(arr) {

            var out = "";
            var i;

            if (arr.length > 0) {
                document.getElementById('results').style.display = "block";
                for (i = 0; i < arr.length; i++) {
                    out += "<p class='address' title='Show Location and Coordinates' onclick='chooseAddr(" + arr[i].lat + ", " + arr[i].lon + ", `" + arr[i].display_name + "`);return false;'>" + arr[i].display_name + "</p>";
                }
                document.getElementById('results').innerHTML = out;
            } else {
                document.getElementById('results').innerHTML = "Sorry, no results...";
            }

        }

        function addr_search() {
            var inp = document.getElementById("addr");
            var xmlhttp = new XMLHttpRequest();
            var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&q=" + inp.value;
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var myArr = JSON.parse(this.responseText);
                    myFunction(myArr);
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }

        // form
        const stepMenuOne = document.querySelector('.formbold-step-menu1')
        const stepMenuTwo = document.querySelector('.formbold-step-menu2')
        const stepMenuThree = document.querySelector('.formbold-step-menu3')

        const stepOne = document.querySelector('.formbold-form-step-1')
        const stepTwo = document.querySelector('.formbold-form-step-2')
        const stepThree = document.querySelector('.formbold-form-step-3')

        const formSubmitBtn = document.querySelector('.formbold-btn')
        const formBackBtn = document.querySelector('.formbold-back-btn')

        formSubmitBtn.addEventListener("click", function (event) {
            event.preventDefault()
            if (stepMenuOne.className == 'formbold-step-menu1 active') {
                event.preventDefault()

                stepMenuOne.classList.remove('active')
                stepMenuTwo.classList.add('active')

                stepOne.classList.remove('active')
                stepTwo.classList.add('active')

                formBackBtn.classList.add('active')
                formBackBtn.addEventListener("click", function (event) {
                    event.preventDefault()

                    stepMenuOne.classList.add('active')
                    stepMenuTwo.classList.remove('active')

                    stepOne.classList.add('active')
                    stepTwo.classList.remove('active')

                    formBackBtn.classList.remove('active')

                })

            } else if (stepMenuTwo.className == 'formbold-step-menu2 active') {
                event.preventDefault()

                stepMenuTwo.classList.remove('active')
                stepMenuThree.classList.add('active')

                stepTwo.classList.remove('active')
                stepThree.classList.add('active')

                formBackBtn.classList.remove('active')
                formSubmitBtn.textContent = 'Submit'
            } else if (stepMenuThree.className == 'formbold-step-menu3 active') {
                document.querySelector('form').submit()
            }
        })
    </script>
</body>

</html>