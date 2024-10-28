<?php
// Iniciar sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión a la base de datos
require_once 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>Debe iniciar sesión para ver esta página.</p>";
    exit();
}

// Inicializar variables para mensajes
$error_message = "";
$success_message = "";

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : null;
    $new_password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

    // Validar contraseñas
    if ($new_password && $confirm_password && $new_password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } elseif ($new_password && (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password))) {
        $error_message = "La contraseña debe tener al menos 8 caracteres y una letra mayúscula.";
    } else {
        // Actualizar el nombre de usuario si no está vacío
        if (!empty($new_username)) {
            $sql_update = "UPDATE entrenadores SET nombre = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $new_username, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username;
                $success_message .= "Nombre de usuario actualizado correctamente. ";
            } else {
                $error_message = "Error al actualizar el nombre de usuario.";
            }
            $stmt->close();
        }

        // Actualizar la contraseña si no está vacía
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE entrenadores SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $success_message .= "Contraseña actualizada correctamente.";
            } else {
                $error_message = "Error al actualizar la contraseña.";
            }
            $stmt->close();
        }
    }

    // Redirigir con mensajes
    if ($error_message) {
        header("Location: Menu_Perfil.html?error=" . urlencode($error_message));
    } else {
        header("Location: Menu_Perfil.html?success=1");
    }
    exit();
}

// Consultar los datos actuales del perfil para mostrar en el formulario
$sql = "SELECT nombre FROM entrenadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($current_username);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
