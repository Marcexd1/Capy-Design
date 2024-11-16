<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Conexión a la base de datos
require 'config.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session_username = $_SESSION['username'];
    $input_username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar que el nombre de usuario ingresado coincida con el de la sesión
    if ($session_username !== $input_username) {
        echo "El nombre de usuario no coincide con el de la sesión.";
        exit();
    }

    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Actualizar la contraseña en la base de datos
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_query = "UPDATE usuarios SET password = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $hashed_password, $session_username);

    if ($update_stmt->execute()) {
        echo "Contraseña actualizada con éxito.";
    } else {
        echo "Error al actualizar la contraseña.";
    }

    $update_stmt->close();
    $conn->close();
}
?>
