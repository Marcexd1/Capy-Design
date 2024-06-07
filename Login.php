<?php
session_start();

// Datos de conexión a la base de datos (cambia estos valores según tu configuración)
$host = "dato de base de datos";
$dbname = "dato de base de datos";
$username = "dato de base de datos";
$password = "dato de base de datos";

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$user = $_POST['username'];
$pass = $_POST['password'];

// Preparar y ejecutar la consulta
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe y la contraseña es correcta
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        // Iniciar sesión
        $_SESSION['username'] = $user;
        echo "Login exitoso. ¡Bienvenido " . $user . "!";
        // Redirigir a la página de inicio u otra página
        // header("Location: inicio.php");
        exit();
    } else {
        echo "Datos Inconclusos.";
    }
} else {
    echo "Datos Inconclusos.";
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>