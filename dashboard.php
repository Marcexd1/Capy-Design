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
    <link rel="stylesheet" href="styles.css"> <!-- Enlace a tu archivo de estilos -->
    <title>Dashboard</title>
</head>
<body>

<!-- Incluir el menú -->
<?php include 'menu.html'; ?>

<!-- Contenido del Dashboard -->
<div class="content">
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Este es tu panel de usuario. Puedes acceder a las diferentes secciones usando el menú de navegación.</p>
</div>

</body>
</html>
