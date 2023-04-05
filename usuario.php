<?php 

//importar 
require 'includes/config/database.php';
$db = conectarDB();

//crear email y password
$email = "correo@correo.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

//query para crearlo 
$query = "INSERT INTO usuarios (email, password) VALUES ('$email', '$passwordHash') ";

// echo $query;

//agregarlo a la base de datos
mysqli_query($db, $query);