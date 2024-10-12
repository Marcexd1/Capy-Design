<?php
// Verificar si la sesión ya está iniciada antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo que contiene la conexión a la base de datos
require_once 'config.php';  // Asegúrate de que 'config.php' define correctamente $conn

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para ver esta página.']);
    exit();
}

// Inicializar variables para mensajes
$success_message = "";
$error_message = "";

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si las claves del formulario están definidas antes de usarlas
    $new_username = isset($_POST['username']) ? $_POST['username'] : null;
    $new_password = isset($_POST['password']) ? $_POST['password'] : null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

    // Validar si las contraseñas coinciden
    if ($new_password && $confirm_password && $new_password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        // Actualizar el nombre de usuario (siempre, aunque no cambie)
        if (!empty($new_username)) {
            $sql_update = "UPDATE entrenadores SET nombre = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $new_username, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username; // Actualizar el nombre en la sesión
                $success_message = "Nombre de usuario actualizado correctamente.";
            } else {
                $error_message = "Error al actualizar el nombre de usuario.";
            }
        }

        // Actualizar la contraseña (siempre, aunque no cambie)
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE entrenadores SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $success_message .= " Contraseña actualizada correctamente.";
            } else {
                $error_message = "Error al actualizar la contraseña.";
            }
        }
    }

    // Devolver respuesta en formato JSON
    if (!empty($error_message)) {
        echo json_encode(['success' => false, 'message' => $error_message]);
    } else {
        echo json_encode(['success' => true, 'message' => $success_message]);
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Menu.css">
    <title>Perfil de Usuario</title>
    <script>
        // Función para enviar los datos del formulario mediante AJAX
        function submitPerfilForm() {
            const form = document.getElementById('perfil-form');
            const formData = new FormData(form); // Obtener los datos del formulario

            // Crear la solicitud AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'perfil.php', true);
            
            // Manejar la respuesta de perfil.php
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText); // Convertir la respuesta JSON
                    const messageContainer = document.getElementById('messages');
                    
                    // Mostrar el mensaje de éxito o error
                    if (response.success) {
                        messageContainer.innerHTML = `<p style="color: green;">${response.message}</p>`;
                    } else {
                        messageContainer.innerHTML = `<p style="color: red;">${response.message}</p>`;
                    }
                } else {
                    console.error("Error al enviar el formulario:", xhr.statusText);
                }
            };

            // Enviar los datos del formulario al servidor
            xhr.send(formData);
        }
    </script>
</head>
<body>

<div class="content">
    <h1>Perfil de Usuario</h1>

    <!-- Mostrar mensajes de éxito o error -->
    <div id="messages"></div> <!-- Contenedor para mostrar mensajes dinámicos -->

    <!-- Formulario para actualizar perfil -->
    <form id="perfil-form" method="POST">
        <div>
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo $current_username; ?>" required>
        </div>
        
        <div>
            <label for="password">Nueva Contraseña (Opcional):</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div>
            <label for="confirm_password">Confirmar Nueva Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <div>
            <button type="button" onclick="submitPerfilForm()">Actualizar Perfil</button> <!-- Botón con función de JS -->
        </div>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
