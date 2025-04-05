<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';

try {
    // Obtener datos del formulario
    $isbn = $_POST['isbn'];
    $email_usuario = $_POST['email_usuario'];

    // Preparar la consulta para insertar un nuevo préstamo
    $sql = "INSERT INTO prestamos (isbn, email_usuario) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind de parámetros
        $stmt->bind_param("ss", $isbn, $email_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Préstamo registrado exitosamente.";
            $_SESSION['mensaje_tipo'] = "exito";
        } else {
            $_SESSION['mensaje'] = "No se pudo registrar el préstamo. Por favor, intenta nuevamente.";
            $_SESSION['mensaje_tipo'] = "error";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['mensaje_tipo'] = "error";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: El préstamo no se pudo registrar.";
    $_SESSION['mensaje_tipo'] = "error";
}

// Cerrar la conexión
$conn->close();

// Redirigir a la página de alta de préstamo
header("Location: ../alta_prestamo.php");
exit;
?>
