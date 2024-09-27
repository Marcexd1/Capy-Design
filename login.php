<?php
session_start();
require_once 'config.php'; // Conectar a la base de datos
require_once 'functions.php'; // Incluir las funciones de cifrado y verificación de contraseñas

// Obtener los datos del formulario de login
$username = $_POST['username'];
$password = $_POST['password'];

// Consultar el nombre de usuario en la base de datos
$sql = "SELECT id, nombre, password FROM entrenadores WHERE nombre = ?";
$stmt = $conn->prepare($sql);
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

        // Verificar si el ID del usuario es 0
        if ($user_id == 0) {
            // Generar una clave temporal
            $temporary_key = bin2hex(random_bytes(16)); // Clave aleatoria de 32 caracteres
            $expiration_time = time() + (30 * 60); // Expira en 30 minutos

            // Guardar la clave en la base de datos (puedes crear una tabla o campo específico para las claves temporales)
            $sql_key = "INSERT INTO claves_temporales (user_id, clave, expiracion) VALUES (?, ?, ?)";
            $stmt_key = $conn->prepare($sql_key);
            $expiration_date = date("Y-m-d H:i:s", $expiration_time); // Guardar el valor en una variable
            $stmt_key->bind_param("iss", $user_id, $temporary_key, $expiration_date);
            $stmt_key->execute();
            $stmt_key->close();

            // Enviar una notificación por correo electrónico
            $to = "rickyolivera2015@gmail.com"; // Reemplazar con el correo electrónico del administrador
            $subject = "Notificación de acceso con ID 0";
            $message = "El usuario con ID 0 ha intentado iniciar sesión. La clave temporal es: " . 
            $temporary_key;
            $headers = "From: s.capyfitness@gmail.com";

            // Envía el correo
            if (mail($to, $subject, $message, $headers)) {
                echo "Se ha enviado una notificación al administrador.";
            } else {
                echo "Error al enviar la notificación.";
            }

            // Redirigir a una página segura o cerrar la sesión automáticamente
            // header("Location: secure.php"); // Puedes redirigir a una página específica si es necesario
            exit();
        }

        echo "Inicio de sesión exitoso. Bienvenido, " . $_SESSION['username'] . ".";

        // Redirigir a la página principal
        header("Location: dashboard.php");
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

$stmt->close();
$conn->close();
?>
