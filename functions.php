<?php 
//include("./db.php");

$giorni = ['Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato', 'Domenica'];
$mesi = ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giungo","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre",];

function getmonth($month){

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
        "08" => $mesi[8],
        "10" => $mesi[9],
        "11" => $mesi[10],
        "12" => $mesi[11],
    ];

    return $m[$month];
}

function giorni($giorno){

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

function prepara_json(){
    include("./db.php");

    //prendo dati
    $sql = "SELECT `id_campagna`, `nome_campagna`, `luogo`, `latitudine`, `longitudine` FROM `campagne`";
    $ris = $conn->query($sql);

    if($ris->num_rows > 0){
        $data = array(); //array che verra messo nel file json
        while($row = $ris->fetch_assoc()){
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
    }else{
        return false;
    }
}

?>

