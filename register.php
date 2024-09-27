<?php
session_start();
require_once 'config.php'; // Conectar a la base de datos
require_once 'functions.php'; // Incluir las funciones de cifrado

// Obtener los datos del formulario de registro
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Verificar si las contraseñas coinciden
if ($password === $confirm_password) {
    // Cifrar la contraseña utilizando SHA-256
    $hashed_password = hashPassword($password);

    // Insertar el nuevo entrenador en la base de datos
    $sql = "INSERT INTO entrenadores (nombre, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    
    if ($stmt->execute()) {
        echo "Registro exitoso. Bienvenido, " . $username . ".";
        header("Location: login.php");
    } else {
        echo "Error al registrar el usuario.";
    }

    $stmt->close();
} else {
    echo "Las contraseñas no coinciden.";
}

$conn->close();
?>
