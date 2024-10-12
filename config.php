<?php
$servername = "localhost";
$username = "root";
$password = ""; // Asumiendo que no hay contraseña para el usuario root
$dbname = "gimnasio";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
