<?php
$server = "localhost"; 
$user = "root";
$pass = "";
$bd = "tienda";



$con = new mysqli($server, $user, $pass, $bd);
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
} 

?>