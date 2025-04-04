<?php
// Incluye el archivo que verifica la sesión del usuario
require '../../autenticacion/check_sesion.php';

// Incluye la conexión a la base de datos
require '../../autenticacion/conexion.php';

// Verifica si se envió el formulario con los datos del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escapa y almacena los datos enviados para prevenir XSS y SQL Injection
    $emailActual = htmlspecialchars($_POST['email_actual']);
    $nombreNuevo = htmlspecialchars($_POST['nombre']);
    $emailNuevo = htmlspecialchars($_POST['email']);
    $adminNuevo = isset($_POST['admin']) ? 1 : 0;

    // Consulta SQL para actualizar el usuario
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, admin = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincula los parámetros y ejecuta la consulta
        $stmt->bind_param('ssis', $nombreNuevo, $emailNuevo, $adminNuevo, $emailActual);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
    }

    // Redirige de vuelta a la lista de usuarios
    header('Location: ../usuarios.php');
    exit;
} else {
    $_SESSION['mensaje'] = "Acción no permitida.";
    header('Location: ../usuarios.php');
    exit;
}
