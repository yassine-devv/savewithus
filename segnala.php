<?php
session_start();
include ("./db.php");
include ("./functions.php");
include ("./ricordami_cookie.php");

if (!isset($_SESSION["iduser"])) {
    header("Location: login.php");
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome_cmp = $conn->real_escape_string(stripslashes($_POST["nome-campagna"]));
    $desc_cmp = $conn->real_escape_string(stripslashes($_POST["descrizione-camp"]));
    $lat = $_POST["lat"];
    $lon = $_POST["lon"];
    $dsp_nm = $_POST["dsp_nm"];
    //$data_cmp = $_POST["data-svolg"];
    $data_cmp = $_POST["y-data"]."-".$_POST["m-data"]."-".$_POST['d-data'];


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

        header("location: user.php?page=campagne");
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
    
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">


    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <?php include "navbar.php" ?>

    <form id="segnalaForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
        <h1>Segnala campagna</h1>
        <!-- One "tab" for each step in the form: -->
        <div class="tab">
            <label>Nome camapagna:</label>
            <p><input placeholder="Dai un nome alla tua campagna" oninput="this.className = ''" name="nome-campagna"></p>
            <!-- <p><input placeholder="Last name..." oninput="this.className = ''" name="lname"></p> -->
            <label>Descrizione:</label>
            <p><textarea name="descrizione-camp" cols="70" rows="7" placeholder="Descrivi un po l'area"></textarea></p>
            <label>Data ritrovo:</label>
            <p>
            <div class="sez-gr">
                <div class="inp custom-select">
                    <select onload="mod_mese(document.getElementById('slct_mese').value)" id="slct_day"
                        name="d-data"></select>
                    <!-- <input type="number" name="d-data"  placeholder="Data" max="31"> -->
                </div>
                <div class="inp custom-select">
                    <select onchange="mod_mese(this.value)" id="slct_mese" name="m-data">
                        <?php
                        foreach ($mesi as $m) {
                            ?>
                            <option value="<?= array_search($m, $mesi) + 1 ?>"><?php echo $m ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <!-- <input type="number" name="m-data"  placeholder="Mese" max="12"> -->
                </div>
                <div class="inp">
                    <input type="number" id="inp-anno" onkeyup="verify_anno(this.value)" name="y-data"
                        placeholder="Anno">
                    <span id="invalid-y"></span>
                </div>
            </div>
            </p>
        </div>
        <div class="tab">
            <p><label>Scegli delle foto che rappresentano l'area che vuoi segnalare:</label></p><br>
            <p><input type="file" name="files[]" multiple></p>
        </div>
        <div class="tab">
            <p>Seleziona il luogo che vuoi: </p>
            <div class="container" style="align-content: center">
                <div class="row">
                    <div class="col">
                        <div id="map"></div>
                    </div>
                    <div class="col">
                        <input type="text" name="lat" id="lat" size=12 value="">
                        <input type="text" name="lon" id="lon" size=12 value="">
                        <input type="text" name="dsp_nm" id="dsp_nm" value="">
        
                        <label for="address" class="formbold-form-label">Inserisci il luogo:</label>
        
                        <div class="row">
                            <!-- <div class="col-8"> -->
                                <input onkeyup="addr_search();" type="text" name="addr" value="" id="addr" size="58" class="formbold-form-input" />
                            <!-- </div> -->

                            <!-- <div class="col-4">
                                <button type="button" style="width: 100%;" class="btn btn-primary" onclick="addr_search();" class="">Cerca</button>
                            </div> -->
                            <br><br>
                            <div id="results"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div style="overflow:auto;">
            <div style="float:right;">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Indietro</button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Avanti</button>
            </div>
        </div>
        <!-- Circles which indicates the steps of the form: -->
        <div style="text-align:center;margin-top:40px;">
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
        </div>
    </form>

    <script>
        // 41.458,12.706
        var startlat = 41.443;
        var startlon = 12.700;

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
                document.getElementById('results').innerHTML = "Nessun risultato...";
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
        //form
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Invia";
            } else {
                document.getElementById("nextBtn").innerHTML = "Avanti";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                document.getElementById("segnalaForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
            }

            if (x[currentTab].getElementsByTagName("textarea").value == "") {
                valid = false;
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }

        const d = new Date();
        let y = d.getFullYear();
        let dd = d.getDay();
        let mm = d.getMonth();

        window.onload = mod_mese(document.getElementById('slct_mese').value);
        window.onload = document.getElementById('inp-anno').value = y;

        function mod_mese(m) {
            let giorni_mesi = { '1': '31', '2': '28', '3': '31', '4': '30', '5': '31', '6': '30', '7': '31', '8': '31', '9': '30', '10': '31', '11': '30', '12': '31' };

            document.getElementById('slct_day').innerHTML = "";

            for (let i = 1; i <= parseInt(giorni_mesi[m]); i++) {
                document.getElementById('slct_day').innerHTML += '<option value="' + i + '">' + i + '</option>';
            }

        }

        function verify_anno(anno) {
            const d = new Date();
            let y = d.getFullYear();
            if (anno < y) {
                document.getElementById('invalid-y').innerHTML = "Anno non valido.";
            } else {
                document.getElementById('invalid-y').innerHTML = "";

            }
        }
    </script>
</body>

</html>