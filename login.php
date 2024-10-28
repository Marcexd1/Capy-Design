<?php
session_start();
require_once 'config.php'; // Conectar a la base de datos
require_once 'functions.php'; // Incluir las funciones de cifrado y verificación de contraseñas

// Validar que el formulario ha sido enviado correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar los datos del formulario de login
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        echo "Por favor, ingrese su nombre de usuario y contraseña.";
        exit();
    }

    // Consultar el nombre de usuario en la base de datos
    $sql = "SELECT id, nombre, contraseña FROM entrenadores WHERE nombre = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta fue preparada correctamente
    if ($stmt === false) {
        // Mostrar el error de MySQL si la consulta no se puede preparar
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit();
    }

    $stmt->bind_param("s", $username); // Nombre de usuario en texto plano (sin cifrar)
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un usuario con ese nombre
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $nombre, $hashed_password);
        $stmt->fetch();

        // Verificar si la contraseña ingresada es correcta
        if (verifyPassword($password, $hashed_password)) {
            // Contraseña válida: iniciar sesión
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $nombre;


            // Redirigir a la página principal
            header("Location: Pantalla.php");
            exit(); // Asegurar que el script se detenga tras la redirección
        } else {
            // Mensaje genérico para evitar exponer si el usuario existe o no
            echo "Credenciales inválidas.";
        }
    } else {
        // Mensaje genérico si no se encuentra el usuario
        echo "Credenciales inválidas.";
    }

    $stmt->close();
} else {
    echo "Método de solicitud no válido.";
}

$conn->close();
?>
