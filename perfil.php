<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: index.html");
    exit();
}

// Incluir la configuración de la base de datos y las funciones
require_once 'config.php';
require_once 'functions.php';

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Inicializar variables para mensajes
$success_message = "";
$error_message = "";

// Si el formulario de actualización fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que las contraseñas coinciden si es que se desea actualizar
    if ($new_password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        // Si hay un nuevo nombre de usuario, actualizarlo
        if (!empty($new_username)) {
            $sql_update = "UPDATE entrenadores SET nombre = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $new_username, $user_id);
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username; // Actualizar la sesión
                $success_message = "Nombre de usuario actualizado.";
            } else {
                $error_message = "Error al actualizar el nombre de usuario.";
            }
        }

        // Si hay una nueva contraseña, actualizarla
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE entrenadores SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success_message .= " Contraseña actualizada.";
            } else {
                $error_message = "Error al actualizar la contraseña.";
            }
        }
    }
}

// Consultar los datos actuales del perfil
$sql = "SELECT nombre FROM entrenadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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
</head>
<body>

<!-- Incluir el menú de navegación -->
<?php include 'Menu.html'; ?>

<div class="content">
    <h1>Perfil de Usuario</h1>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Formulario para actualizar perfil -->
    <form action="perfil.php" method="POST">
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
            <input type="submit" value="Actualizar Perfil">
        </div>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
