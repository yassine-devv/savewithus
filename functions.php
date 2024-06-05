<?php
//include("./db.php");


$giorni = ['Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato', 'Domenica'];
$mesi = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giungo", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre",];

function getmonth($month)
{

    global $mesi;

    $m = [
        "01" => $mesi[0],
        "02" => $mesi[1],
        "03" => $mesi[2],
        "04" => $mesi[3],
        "05" => $mesi[4],
        "06" => $mesi[5],
        "07" => $mesi[6],
        "08" => $mesi[7],
        "09" => $mesi[8],
        "10" => $mesi[9],
        "11" => $mesi[10],
        "12" => $mesi[11],
    ];

    return $m[$month];
}

function giorni($giorno)
{

    global $giorni;

    $g = [
        "01" => $giorni[0],
        "02" => $giorni[1],
        "03" => $giorni[2],
        "04" => $giorni[3],
        "05" => $giorni[4],
        "06" => $giorni[5],
        "07" => $giorni[6],
    ];

    return $g[$giorno];
}

function prepara_json()
{
    include ("./db.php");

    //prendo dati
    $sql = "SELECT `id_campagna`, `nome_campagna`, `luogo`, `latitudine`, `longitudine`, stato FROM `campagne`";
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        $data = array(); //array che verra messo nel file json
        while ($row = $ris->fetch_assoc()) {
            $item = array(
                "campagna" => array(
                    "id" => $row["id_campagna"],
                    "nome_campagna" => $row['nome_campagna'],
                    "stato" => $row["stato"],
                    "luogo" => $row['luogo'],
                    "lat" => $row['latitudine'],
                    "lon" => $row['longitudine']
                ),
            );

            array_push($data, $item);

        }
        $json = json_encode($data);
        // Generate json file
        file_put_contents("data.json", $json);
    } else {
        return false;
    }
}

if (isset($_GET["id_cmp"])) {
    include ("./db.php");
    $id = $_GET["id_cmp"];

    $sql = "SELECT foto, `latitudine`, `longitudine` FROM `campagne` where id_campagna=" . $id;
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        $data = array(); //array che verra messo nel file json
        while ($row = $ris->fetch_assoc()) {
            $item = array(
                "campagna" => array(
                    "id" => $id,
                    "foto" => $row['foto'],
                    "lat" => $row['latitudine'],
                    "lon" => $row['longitudine']
                ),
            );

            array_push($data, $item);

        }
        $json = json_encode($data);
        // Generate json file
        //file_put_contents("data.json", $json);
        echo $json;
    } else {
        return false;
    }
}

if (isset($_GET['iscrizione'])) {
    session_start();
    include ('./db.php');

    $resp = [];

    if (isset($_SESSION['iduser'])) {
        $sql = "INSERT INTO `partecipanti_camapgne`(`id_user`, `id_campagna`) VALUES (" . $_SESSION['iduser'] . "," . $_GET['iscrizione'] . ")";
        $ris = $conn->query($sql);

        if ($ris) {
            $resp = ['result' => true, 'msg' => "Iscrizione alla campagna avvenuta con successo!"];
        } else {
            $resp = ['result' => false, 'msg' => "Errore durante l'iscrizione, riprova!"];
        }

    } else {
        $resp = ['result' => "0", 'msg' => "Per iscriverti alla campagna, esegui il login!"];
    }

    //echo $resp;
    echo json_encode($resp);
    //echo $_SESSION['iduser'];
}

if (isset($_GET['annulla'])) {
    session_start();
    include ('./db.php');

    $sql = "DELETE FROM `partecipanti_camapgne` WHERE partecipanti_camapgne.id_user=" . $_SESSION['iduser'] . " and partecipanti_camapgne.id_campagna=" . $_GET['annulla'];

    $resp = [];
    if ($conn->query($sql) === TRUE) {
        $resp = ['result' => true, 'msg' => "Iscrizione annullata con successo!"];
    } else {
        $resp = ['result' => false, 'msg' => "Errore, riprova!"];
    }
    echo json_encode($resp);

}

if (isset($_GET['Commenti'])) {
    session_start();
    include ('./db.php');

    $sql = "SELECT campagne.giorno_ritrovo, partecipanti_camapgne.id_user,partecipanti_camapgne.id_campagna, partecipanti_camapgne.commento, utenti.username FROM campagne join partecipanti_camapgne on campagne.id_campagna=partecipanti_camapgne.id_campagna join utenti on partecipanti_camapgne.id_user=utenti.id_user WHERE partecipanti_camapgne.id_campagna=" . $_GET['Commenti'];
    $ris = $conn->query($sql);

    $resp = [];

    $datadisp = false;
    if ($ris->num_rows > 0) {
        $data = [];
        while ($row = $ris->fetch_assoc()) {
            //echo $row['commento'];
            //if (date("Y-m-d") >= $row['giorno_ritrovo']) { //controllo se l'evento è stato fatto o meno
                $datadisp = true;
                if ($row['commento'] !== null) {
                    array_push($data, $row);
                }
            //} else {
            //    $resp = ['result' => false, 'msg' => "Commenti ancora non disponibili!"];
            //    echo json_encode($resp);
            //    return;
            //}
        }

        if ($datadisp) {
            if (count($data) == 0) {
                $data = ['result' => false, 'msg' => "Nessun commento disponibile!"];
                echo json_encode($data);
            } else {
                $data['result'] = true;
                echo json_encode($data);
            }
        }
    } else {
        $resp = ['result' => false, 'msg' => "Nessun commento disponibile!"];
        echo json_encode($resp);
    }
}

if (isset($_GET['addcomment']) && isset($_GET['id'])) {
    session_start();
    include ('./db.php');
    //vedere prima se l'utente è loggato, se iscritto, se l'evento è stato tneuto

    if (isset($_SESSION['iduser'])) {
        $sql = "SELECT campagne.id_campagna, campagne.giorno_ritrovo, partecipanti_camapgne.id_user, partecipanti_camapgne.id_campagna, partecipanti_camapgne.commento FROM partecipanti_camapgne join campagne on partecipanti_camapgne.id_campagna=campagne.id_campagna where partecipanti_camapgne.id_user=" . $_SESSION['iduser'] . " and partecipanti_camapgne.id_campagna=" . $_GET['id'];
        $ris = $conn->query($sql);

        if ($ris->num_rows > 0) { //utente iscritto alla campagna
            while ($row = $ris->fetch_assoc()) {
                //if ($row['giorno_ritrovo'] <= date("Y-m-d")) { //controllo se l'evento è stato fatto o meno
                    $sql = "UPDATE partecipanti_camapgne SET partecipanti_camapgne.commento='" . addslashes($_GET['addcomment']) . "' WHERE partecipanti_camapgne.id_campagna=" . $_GET['id'] . " and partecipanti_camapgne.id_user=" . $_SESSION['iduser'];

                    $resp = [];
                    if ($conn->query($sql) === TRUE) {
                        $resp = ['result' => true, 'msg' => "Commento inserito con successo"];
                    } else {
                        $resp = ['result' => false, 'msg' => "Errore durante l'inserimento"];
                    }
                //} else {
                    //$resp = ['result' => false, 'msg' => "Impossibile aggiungere un commento perchè l'evento non è ancora stato tenuto."];
                    //echo json_encode($resp);
                    //return;
                //}
            }
        } else {
            $resp = ['result' => false, 'msg' => "Eseguire prima l'iscrizione alla campagna"];
        }
    } else {
        $resp = ['result' => false, 'msg' => "Eseguire prima l'accesso per aggiungere un commento"];
    }

    echo json_encode($resp);

}

if (isset($_GET['Partecipanti'])) {
    session_start();
    include ('./db.php');

    $resp = array();

    $sql_count = "Select COUNT(partecipanti_camapgne.id_user) as n_partecipanti from partecipanti_camapgne where partecipanti_camapgne.id_campagna=" . $_GET['Partecipanti'];
    $ris_count = $conn->query($sql_count);

    $row_cont = $ris_count->fetch_assoc();

    if ($row_cont['n_partecipanti'] !== '0') {
        $sql = "Select utenti.username, partecipanti_camapgne.id_user, partecipanti_camapgne.id_campagna from partecipanti_camapgne join utenti on partecipanti_camapgne.id_user=utenti.id_user where partecipanti_camapgne.id_campagna=" . $_GET['Partecipanti'];
        $ris = $conn->query($sql);

        $resp['data'] = array();
        $resp['result'] = false;
        $resp['n_part'] = $row_cont['n_partecipanti'];
        while ($row = $ris->fetch_assoc()) {
            $resp['result'] = true;
            array_push($resp['data'], ["id" => $row['id_user'], "username" => $row['username']]);
        }
    } else {
        $resp = ['result' => false, 'msg' => "Nessun volontario al momento iscritto in questa campagna", 'n_part' => $row_cont['n_partecipanti']];
    }

    echo json_encode($resp);

}

if (isset($_GET['blog_content'])) {
    include ('./db.php');

    $sql = "SELECT blog.testo FROM blog WHERE blog.id_blog=" . $_GET['blog_content'];
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        $row = $ris->fetch_assoc();
        echo $row['testo'];
    }

}

if (isset($_GET['deleteblog'])) {
    include ('./db.php');

    $sql = "UPDATE blog SET blog.stato='0' WHERE blog.id_blog=" . $_GET['deleteblog'];
    //$ris = $conn->query($sql);

    if ($conn->query($sql)) {
        header('location: user.php?page=blog');
    } else {
        echo "Errore durante la rimozione";
    }

}

function get_page_user($page)
{
    include ("./db.php");

    $sql = "SELECT * FROM utenti WHERE utenti.id_user=" . $_SESSION['iduser'];
    $ris = $conn->query($sql);
    $data = [];

    if ($ris->num_rows == 1) {
        $data = $ris->fetch_assoc();
    }

    switch ($page) {
        case 'dati_profilo';
            ?>
            <div class="cont-div">
                <h1>Dati profilo</h1>
                <div class="data_profile">
                    <div class="div-form-data">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <label>Nome:</label>
                                        <input type="text" name="nome" value="<?= $data['nome'] ?>" class="input">
                                    </div>
                                    <div class="col">
                                        <label>Cognome:</label>
                                        <input type="text" name="cognome" value="<?= $data['cognome'] ?>" class="input">
                                    </div>
                                </div>
                                <br>
                                <label>Username:</label><br>
                                <input type="text" name="username" value="<?= $data['username'] ?>" style="width: 100%;"
                                    class="input">
                                <br><br>
                                <label>N. telefono</label><br>
                                <input type="text" name="tel" value="<?= $data['num_tel'] ?>" style="width: 100%;" class="input">
                                <br><br>
                                <label>E-mail</label><br>
                                <input type="email" name="email" value="<?= $data['email'] ?>" style="width: 100%;"
                                    class="input"><br><br>
                                <input class="btn btn-primary" type="submit" value="Modifica" name="agg-dati" class="input">
                                <button type="button" class="btn btn-danger" class="input"
                                    onclick="window.location.href='loggout.php'">Esci</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            break;
        case 'blog':
            ?>
            <div class="cont-div">
                <h1>Blog</h1>
                <button onclick="window.location.href = 'new_blog.php' " class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-plus-fill"
                        viewBox="0 0 16 16">
                        <path
                            d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zM8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0" />
                    </svg>

                    Pubblica blog
                </button>
                <div class="attesa">
                    <br>
                    <h3>In attesa</h3>
                    <div class="cards-blogs">
                        <?php
                        $sql = "SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.autore FROM `blog` WHERE blog.stato=1 and blog.autore='" . $_SESSION['iduser'] . "'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <img class="card-img-top" src="./imgs/img-bg.jpeg" alt="Card image cap"><br><br>
                                        <h5 class="card-title"><?= $row['titolo'] ?></h5>
                                        <p class="card-text">Creato il: <?= date('d M Y', strtotime($row['created'])); ?></p>
                                        <!-- <a href="blog.php?id=<?= $row['id_blog'] ?>" class="btn btn-primary">Modifica</a> -->
                                    </div>
                                </div>
                                <?php
                            }

                        } else {
                            echo '<h4>Nessun blog in attesa</h4>';
                        }

                        ?>
                    </div>
                    <br>
                    <h3>Pubblicati</h3>
                    <div class="cards-blogs">
                        <?php
                        $sql = "SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.autore FROM `blog` WHERE blog.stato=2 and blog.autore='" . $_SESSION['iduser'] . "'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <img class="card-img-top" src="./imgs/img-bg.jpeg" alt="Card image cap"><br><br>
                                        <h5 class="card-title"><?= $row['titolo'] ?></h5>
                                        <p class="card-text">Creato il: <?= date('d M Y', strtotime($row['created'])); ?></p>
                                        <button onclick="window.location.href = 'new_blog.php?id=<?= $row['id_blog'] ?>'"
                                            class="btn btn-primary">Modifica</button>
                                        <button onclick="window.location.href = 'blog.php?id=<?= $row['id_blog'] ?>'"
                                            class="btn btn-primary">Leggi</button>
                                        <button onclick="window.location.href = 'functions.php?deleteblog=<?= $row['id_blog'] ?>'"
                                            type="button" class="btn btn-danger">Elimina</button>
                                        <!-- <a href="blog.php?id=<?= $row['id_blog'] ?>" class="btn btn-primary">Modifica</a> -->
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
            </div>
            <?php
            break;
        case 'campagne':
            ?>
            <div class="cont-div">
                <h1>Campagne</h1>
                <button onclick="window.location='segnala.php'" type="button" class="btn btn-warning">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>

                    Segnala
                </button>

                <div class="attesa">
                    <br>
                    <h3>In attesa</h3>
                    <div class="cards-blogs">
                        <?php
                        $sql = "SELECT campagne.id_campagna, campagne.nome_campagna, campagne.giorno_ritrovo, campagne.luogo FROM `campagne` WHERE campagne.stato='1' and campagne.autore='" . $_SESSION['iduser'] . "'";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $row['nome_campagna'] ?></h5>
                                        <p class="card-text">Luogo: <?= $row['giorno_ritrovo'] ?></p>
                                        <p class="card-text">Giorno: <?= date('d M Y', strtotime($row['giorno_ritrovo'])); ?></p>
                                        <!-- <a href="blog.php?id=<?= $row['id_blog'] ?>" class="btn btn-primary">Modifica</a> -->
                                    </div>
                                </div>
                                <?php
                            }

                        } else {
                            echo '<h4>Nessuna camapgna in attesa</h4>';
                        }

                        ?>
                    </div>
                </div>
                <br>
                <h3>Segnalati</h3>
                <div class="attesa">


                    <div class="cards-blogs">
                        <?php
                        $sql = "SELECT campagne.id_campagna, campagne.nome_campagna, campagne.giorno_ritrovo, campagne.luogo FROM `campagne` WHERE campagne.stato='2' and campagne.autore='" . $_SESSION['iduser'] . "'";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $row['nome_campagna'] ?></h5>
                                        <p class="card-text">Giorno: <?= date('d M Y', strtotime($row['giorno_ritrovo'])); ?></p>
                                        <p class="card-text">Luogo: <?= $row['luogo'] ?></p>

                                        <button onclick="window.location.href = 'campagne.php?id=<?= $row['id_campagna'] ?>'"
                                            class="btn btn-primary">Vedi</button>
                                        <button onclick="window.location.href = 'campagne.php?id=<?= $row['id_campagna'] ?>'"
                                            class="btn btn-primary">Modifica</button>

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
            </div>
            <?php
            break;
    }
    ?>

    <?php
}

?>