<?php
session_start();
include ('db.php');

if (isset($_SESSION['iduser'])) {
    $iduser = $_SESSION['iduser'];
    session_destroy();

    // Rimuove il cookie e il token dal database
    if (isset($_COOKIE['ricordami'])) {
        $token = $_COOKIE['ricordami'];
        setcookie('ricordami', '', time() - 3600, "/", "", true, true);

        $sql_dlt_tk = "DELETE FROM tokens_utenti WHERE tokens_utenti.id_user = '".$iduser."' AND tokens_utenti.token = '".$token."'";
        $ris = $conn->query($sql_dlt_tk);
    }
}
header("Location: login.php");
exit(0);

?>