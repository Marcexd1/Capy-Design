<?php
// Iniciar la sesión
session_start();

// Destruir todas las sesiones
session_destroy();

// Redirigir al formulario de login
header("Location: index.html");
exit();
?>
