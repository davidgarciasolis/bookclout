<?php
require '../../autenticacion/check_sesion.php';
// Incluye la conexión a la base de datos
require '../../autenticacion/conexion.php';

// Verificar que la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el ID de la reserva desde el formulario
    $idReserva = $_POST['id_reserva'];

    // Preparar la consulta para actualizar el estado de la reserva a "cancelada"
    $sql = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = ? AND estado = 'activa'";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idReserva);

    if ($stmt->execute()) {
        // Mensaje de éxito
        $_SESSION['mensaje'] = 'La reserva ha sido cancelada con éxito.';
    } else {
        // Mensaje de error
        $_SESSION['mensaje'] = 'Hubo un error al cancelar la reserva.';
    }

    // Cerrar el statement y la conexión
    $stmt->close();
    $conn->close();

    // Redirigir al usuario de vuelta a la página de reservas
    header('Location: ../reservas.php');
    exit();
} else {
    // Si la solicitud no es POST, redirigir a la página principal de reservas
    header('Location: ../reservas.php');
    exit();
}
?>
