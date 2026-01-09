<?php
$servername = "sql300.infinityfree.com";
$username = "if0_40288804";
$password = "DaAn2419";
$dbname =  "if0_40288804_abarrotes_anita";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>