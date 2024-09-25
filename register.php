register.php

<?php
session_start();
require_once 'config.php'; // Conectar a la base de datos
require_once 'functions.php'; // Incluir las funciones de cifrado y descifrado

// Obtener los datos del formulario de registro
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Verificar si las contraseñas coinciden
if ($password === $confirm_password) {
    // Cifrar la contraseña utilizando hashPassword (para contraseñas, no usamos descifrado)
    $hashed_password = hashPassword($confirm_password);

    // Si deseas cifrar otros datos (como el nombre de usuario):
    $encrypted_username = encryptData($username);

    // Insertar el nuevo entrenador en la base de datos
    $sql = "INSERT INTO entrenadores (nombre, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    
    if ($stmt->execute()) {
        echo "Registro exitoso. Bienvenido, " . decryptData($encrypted_username) . ".";
        header("Location: index.html");
    } else {
        echo "Error al registrar el usuario.";
    }

    $stmt->close();
} else {
    echo "Las contraseñas no coinciden.";
}

$conn->close();
?>
