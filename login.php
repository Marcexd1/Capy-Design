

<?php
session_start();
require_once 'config.php'; // Conectar a la base de datos
require_once 'functions.php'; // Incluir las funciones de cifrado y descifrado

// Obtener los datos del formulario de login
$username = $_POST['username'];
$password = $_POST['password'];

// Consultar el nombre de usuario en la base de datos
$sql = "SELECT id, nombre, password FROM entrenadores WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // Usa el nombre de usuario sin cifrado

$stmt->execute();
$stmt->store_result();

// Verificar si se encontró un usuario con ese nombre
if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $encrypted_username, $hashed_password);
    $stmt->fetch();

    // Verificar si la contraseña ingresada es correcta
    if (verifyPassword($password, $hashed_password)) {
        // Contraseña válida: iniciar sesión
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = decryptData($encrypted_username);
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
