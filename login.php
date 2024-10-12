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

            // Verificar si el ID del usuario es 0
            if ($user_id === 0) {
                // Generar una clave temporal
                $temporary_key = bin2hex(random_bytes(16)); // Clave aleatoria de 32 caracteres
                $expiration_time = time() + (30 * 60); // Expira en 30 minutos

                // Guardar la clave en la base de datos (debe existir una tabla para almacenar claves temporales)
                $sql_key = "INSERT INTO claves_temporales (user_id, clave, expiracion) VALUES (?, ?, ?)";
                $stmt_key = $conn->prepare($sql_key);
                $expiration_date = date("Y-m-d H:i:s", $expiration_time); // Convertir la expiración a formato de fecha
                $stmt_key->bind_param("iss", $user_id, $temporary_key, $expiration_date);
                $stmt_key->execute();
                $stmt_key->close();

                // Enviar una notificación por correo electrónico al administrador
                $to = "rickyolivera2015@gmail.com"; // Correo del administrador
                $subject = "Notificación de acceso con ID 0";
                $message = "El usuario con ID 0 ha intentado iniciar sesión. La clave temporal es: " . $temporary_key;
                $headers = "From: s.capyfitness@gmail.com";

                if (mail($to, $subject, $message, $headers)) {
                    echo "Se ha enviado una notificación al administrador.";
                } else {
                    echo "Error al enviar la notificación.";
                }

                // Redirigir o finalizar la sesión
                // header("Location: secure.php"); // Opcional: redirigir a una página segura
                exit();
            }

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
