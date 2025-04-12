<?php
// Incluir archivo para verificar la sesión activa
require '../../autenticacion/check_sesion.php';
// Incluye la conexión a la base de datos
require '../../autenticacion/conexion.php';

// Verifica si se recibió el ID del préstamo
if (isset($_POST['id_prestamo'])) {
    $idPrestamo = intval($_POST['id_prestamo']); // Sanitiza el ID para evitar inyecciones SQL

    // Obtén la fecha actual
    $fechaActual = date('Y-m-d');

    // Actualiza la fecha de devolución en la base de datos
    $sql = "UPDATE prestamos SET fecha_devolucion = ? WHERE id_prestamo = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('si', $fechaActual, $idPrestamo);
        if ($stmt->execute()) {
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Préstamo con ID $idPrestamo marcado como entregado.";
        } else {
            // Mensaje de error
            $_SESSION['mensaje'] = "Error al actualizar el préstamo: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
    }

    // Redirige a la página de préstamos
    header('Location: ../prestamos.php');
    exit();
} else {
    // Maneja el caso en el que no se recibe el ID
    $_SESSION['mensaje'] = "ID de préstamo no válido.";
    header('Location: ../prestamos.php');
    exit();
}

// Cierra la conexión
$conn->close();
?>
