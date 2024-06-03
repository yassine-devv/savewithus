<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include ('db.php');

if (!isset($_SESSION['iduser']) && isset($_COOKIE['ricordami'])) {
    $token = $_COOKIE['ricordami'];

    $sql = "SELECT tokens_utenti.id_user, utenti.username FROM utenti join tokens_utenti on utenti.id_user=tokens_utenti.id_user WHERE tokens_utenti.token = '" . $token . "' AND tokens_utenti.scandenza > NOW()";
    $ris = $conn->query($sql);
    $row_tk = $ris->fetch_assoc();

    if ($ris->num_rows > 0) {
        $_SESSION['iduser'] = $row_tk['id_user'];
        $_SESSION['username'] = $row_tk['username'];
        header("Location: user.php");
        exit(0);
    }
}

?>