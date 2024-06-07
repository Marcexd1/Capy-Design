<?php
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

// Obtener datos del formulario de registro
$user = $_POST['username'];
$pass = $_POST['password'];

// Hashear la contraseña
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Preparar y ejecutar la consulta de inserción
$sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $hashed_password);

if ($stmt->execute()) {
    echo "Registro exitoso. Ahora puedes iniciar sesión.";
} else {
    echo "Error en el registro: " . $stmt->error;
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
