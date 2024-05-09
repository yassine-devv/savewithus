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
    $sql = "SELECT `id_campagna`, `nome_campagna`, `luogo`, `latitudine`, `longitudine` FROM `campagne`";
    $ris = $conn->query($sql);

    if ($ris->num_rows > 0) {
        $data = array(); //array che verra messo nel file json
        while ($row = $ris->fetch_assoc()) {
            $item = array(
                "campagna" => array(
                    "id" => $row["id_campagna"],
                    "nome_campagna" => $row['nome_campagna'],
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
    
    $sql = "SELECT foto, `latitudine`, `longitudine` FROM `campagne` where id_campagna=".$id;
    $ris = $conn->query($sql);
    
    if($ris->num_rows > 0){
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
    
    $sql = "DELETE FROM `partecipanti_camapgne` WHERE partecipanti_camapgne.id_user=".$_SESSION['iduser']." and partecipanti_camapgne.id_campagna=".$_GET['annulla'];
    
    $resp = [];
    if ($conn->query($sql) === TRUE) {
        $resp = ['result' => true, 'msg' => "Iscrizione annullata con successo!"];
    } else {
        $resp = ['result' => false, 'msg' => "Errore, riprova!"];
    }
    echo json_encode($resp);
    
}

if(isset($_GET['Commenti'])){
    session_start();
    include ('./db.php');
    
    $sql = "SELECT campagne.giorno_ritrovo, partecipanti_camapgne.id_user,partecipanti_camapgne.id_campagna, partecipanti_camapgne.commento, utenti.username FROM campagne join partecipanti_camapgne on campagne.id_campagna=partecipanti_camapgne.id_campagna join utenti on partecipanti_camapgne.id_user=utenti.id_user WHERE partecipanti_camapgne.id_campagna=" . $_GET['Commenti'];
    $ris = $conn->query($sql);
    
    $resp = [];
    
    $datadisp = false;
    if ($ris->num_rows > 0) {
        $data = [];
        while($row = $ris->fetch_assoc()){
            if($row['giorno_ritrovo'] >= date("Y-m-d")){ //controllo se l'evento è stato fatto o meno
                $datadisp = true;
                array_push($data, $row);
            }else{
                $resp = ['result' => false, 'msg' => "Commenti ancora non disponibili!"];
                echo json_encode($resp);
                return;
            }
        }
        if($datadisp){
            $data['result'] = true;
            echo json_encode($data);
        }
    } else {
        $resp = ['result' => false, 'msg' => "Nessun commento disponibile!"];
        echo json_encode($resp);
    }
}

if(isset($_GET['addcomment']) && isset($_GET['id'])){
    session_start();
    include ('./db.php');
    
    $sql = "UPDATE partecipanti_camapgne SET partecipanti_camapgne.commento='".$_GET['addcomment']."' WHERE partecipanti_camapgne.id_campagna=".$_GET['id']." and partecipanti_camapgne.id_user=".$_SESSION['iduser'];
    
    $resp = [];
    if ($conn->query($sql) === TRUE) {
        $resp = ['result' => true, 'msg' => "Commento inserito con successo"];
    } else {
        $resp = ['result' => false, 'msg' => "Errore durante l'inserimento"];
    }
    
    echo json_encode($resp);

}

?>