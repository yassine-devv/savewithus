<?php 
include("./db.php");

$username = $_GET['username'];
$password = $_GET['password'];

$hashpass = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO `amministratori`(`username`, `password`) VALUES ('".$username."','".$hashpass."')";

$conn->query($sql);

?>