<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Menu.css"> <!-- Enlace a tu archivo de estilos -->
    <title>Dashboard</title>
</head>
<body>

<!-- Incluir el menú -->
<?php include 'Menu.html'; ?>

</body>
</html>
