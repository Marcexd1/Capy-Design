<?php
include('config.php');

// Habilitar visualización de errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$peso = isset($_POST['peso']) ? trim($_POST['peso']) : null;
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$message = '';
$message_type = '';

// Procesar la solicitud solo si es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validación básica
    if (empty($nombre) || empty($direccion)) {
        $message = 'Todos los campos obligatorios deben ser llenados.';
        $message_type = 'error';
    } else {
        // Iniciar una transacción
        $conn->begin_transaction();

        // Datos de la membresía
        $status = 1; // Estado activo por defecto
        $f_start = NULL; // Dejar el campo vacío
        $f_expired = NULL; // Dejar el campo vacío

        // Preparar la consulta SQL para insertar la membresía
        $stmt_membresia = $conn->prepare("INSERT INTO membrecias (Status, F_Start, F_Expired, F_Creacion) VALUES (?, ?, ?, NOW(6))");
        if ($stmt_membresia === false) {
            $message = 'Error en la preparación de la consulta de membresía: ' . $conn->error;
            $message_type = 'error';
        } else {
            $stmt_membresia->bind_param("iss", $status, $f_start, $f_expired);
            if ($stmt_membresia->execute()) {
                // Obtener el ID de la membresía recién insertada
                $id_m = $stmt_membresia->insert_id;

                // Preparar la consulta SQL para insertar el cliente
                $stmt_cliente = $conn->prepare("INSERT INTO clientes (nombre, Peso, direccion, id_m) VALUES (?, ?, ?, ?)");
                if ($stmt_cliente === false) {
                    $conn->rollback();
                    $message = 'Error en la preparación de la consulta de cliente: ' . $conn->error;
                    $message_type = 'error';
                } else {
                    $stmt_cliente->bind_param("sdsi", $nombre, $peso, $direccion, $id_m);
                    if ($stmt_cliente->execute()) {
                        // Confirmar la transacción
                        $conn->commit();
                        $message = 'Nuevo cliente y membresía agregados exitosamente.';
                        $message_type = 'success';
                    } else {
                        // Revertir la transacción en caso de error
                        $conn->rollback();
                        $message = 'Error al insertar el cliente: ' . $stmt_cliente->error;
                        $message_type = 'error';
                    }
                    $stmt_cliente->close();
                }
            } else {
                // Revertir la transacción en caso de error
                $conn->rollback();
                $message = 'Error al insertar la membresía: ' . $stmt_membresia->error;
                $message_type = 'error';
            }
            $stmt_membresia->close();
        }
    }

    // Cerrar la conexión
    mysqli_close($conn);

    // Redireccionar con mensaje
    header("Location: Menu_Agregar_Clientes.html?message=" . urlencode($message) . "&type=" . urlencode($message_type));
    exit;
}
?>