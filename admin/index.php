<?php 
session_start();
include("../db.php");

if(!isset($_SESSION['idamm'])){
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amministratore</title>
</head>
<body>
    <?php echo $_SESSION['idamm'] ?>
</body>
</html>