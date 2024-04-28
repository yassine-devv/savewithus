<?php 
$username = "root";
$password = "";
$hostname = "localhost";
$db = "savewithus";

$conn = mysqli_connect($hostname, $username, $password, $db);

if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}


?>