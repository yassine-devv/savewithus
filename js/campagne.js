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
                if(data[i]['campagna']['stato']!=="1"){
                    var marker = L.marker([data[i]['campagna']['lat'], data[i]['campagna']['lon']]).addTo(map);
                }
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
                        var marker = L.marker([data[i]['campagna']['lat'], data[i]['campagna']['lon']]).addTo(map);
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

            document.slide.src = img[i];

            let btnPross = document.getElementById("pross");
            btnPross.addEventListener("click", prossImg);

            let btnPrec = document.getElementById("prec");
            btnPrec.addEventListener("click", precImg);
        }
    }
    xmlhttp.open("GET", "functions.php?id_cmp=" + c, true);
    xmlhttp.send();


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

    function azioni_campagna(azione, id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.response);

                if (azione == 'iscrizione') {
                    console.log(data);

                    if (data['result'] == true) {
                        alert(data['msg']);
                        let oldbtn = document.querySelector(".col-4 .btn-warning");
                        oldbtn.remove();

                        let btniscr = '<button type="button" class="btn btn-danger" onclick="azioni_campagna(\'annulla\', ' + c + ')">Annulla iscrizione<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" /></svg></button>';
                        document.querySelector(".col-4").innerHTML += btniscr;
                    }

                    if (data['result'] == false) {
                        alert(data['msg']);
                    }
                    if (data['result'] == "0") {
                        location.replace("login.php");
                    }
                }

                if (azione == "annulla") {
                    if (data['result'] == true) {
                        alert(data['msg']);
                        let oldbtn = document.querySelector(".col-4 .btn-danger");
                        oldbtn.remove();

                        let btniscr = '<button id="iscr_cmp" type="button" class="btn btn-warning" onclick="azioni_campagna(\'iscrizione\', ' + c + ')">Iscriviti alla campagna</button>';
                        document.querySelector(".col-4").innerHTML += btniscr;

                    }
                }

            }

        }
        xmlhttp.open("GET", "functions.php?" + azione + "=" + id, true);
        xmlhttp.send();
    }

}

function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);

            console.log(data);

            if (tabName == "Commenti") {
                document.getElementById("content").innerHTML = "";
                console.log(Object.keys(data).length--);
                if (data['result'] == false) {
                    document.getElementById("content").innerHTML += data['msg'];
                }

                if (data['result'] == true) {
                    let count = Object.keys(data).length - 1;
                    //console.log(data[0]['username']);
                    for (let i = 0; i < count; i++) {

                        document.getElementById("content").innerHTML += "<span>" + data[i]['username'] + ": " + data[i]['commento'] + "</span><br>";
                    }
                }
            }

            if (tabName == "Partecipanti") {
                document.getElementById(tabName).innerHTML = "";
                if (data['result'] == false) {
                    document.getElementById(tabName).innerHTML += data["msg"];
                }

                if (data['result'] == true) {
                    let count = Object.keys(data).length;
                    //console.log(data[0]['username']);
                    for (let i = 0; i < count; i++) {
                        //let card = "<div class=\"card\" style=\"width: 18rem;\"><div class=\"card-body\"><h5 class=\"card-title\">" + data['data'][i]['username'] + "</h5><a class=\"card-link\" href='profile.php?id=" + data['data'][i]['id'] + "'>Profilo</a></div></div>";
                        //console.log(data['data'][i]);
                        document.getElementById(tabName).innerHTML += "<div class=\"card\" style=\"width: 18rem;\"><div class=\"card-body\"><h5 class=\"card-title\">" + data['data'][i]['username'] + "</h5><a class=\"card-link\" href='profile.php?id=" + data['data'][i]['id'] + "'>Profilo</a></div></div>";
                    }

                }
            }


        }
    }
    xmlhttp.open("GET", "functions.php?" + tabName + "=" + c, true);
    xmlhttp.send();
}
if(document.body.contains(document.getElementById("mapcmp"))){
    document.getElementById("defaultOpen").click();
}

function addcomment() {
    let comment = document.getElementById('inp-commento').value;
    if (comment.length !== 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.response);
                alert(data['msg']);
                window.location = 'campagne.php?id=' + c;
            }
        }
        xmlhttp.open("GET", "functions.php?id=" + c + "&addcomment=" + comment, true);
        xmlhttp.send();
    }
}

function view_tab_mod() {
    console.log("ciao");
    let box = document.querySelector(".box-modify-camp");
    
    if (box.style.display == "block") {
        box.style.display = "none";
        document.body.style.overflow = "scroll";
    } else {
        box.style.display = "block";
        document.body.style.overflow = "hidden";
        //document.body.style.backdropFilter = "brightness(50%)";
    }
    
}

function close_box(){
    let box = document.querySelector(".box-modify-camp");

    box.style.display = "none";
    document.body.style.overflow = "scroll";
}