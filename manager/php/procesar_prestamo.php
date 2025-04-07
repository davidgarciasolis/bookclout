<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idReserva = $_POST['id_reserva'];

    // Consulta para convertir la reserva en préstamo
    $sqlPrestamo = "INSERT INTO prestamos (isbn, email_usuario)
                    SELECT r.isbn, r.email_usuario
                    FROM reservas r
                    WHERE r.id_reserva = ? AND r.estado = 'activa'";

    $sqlActualizarReserva = "UPDATE reservas SET estado = 'prestado' WHERE id_reserva = ?";

    $stmt = $conn->prepare($sqlPrestamo);
    $stmt->bind_param("i", $idReserva);

    if ($stmt->execute()) {
        $stmt = $conn->prepare($sqlActualizarReserva);
        $stmt->bind_param("i", $idReserva);
        $stmt->execute();

        $_SESSION['mensaje'] = 'La reserva se ha convertido en préstamo con éxito.';
    } else {
        $_SESSION['mensaje'] = 'Hubo un error al procesar el préstamo.';
    }

    $stmt->close();
    $conn->close();

    header('Location: reservas.php');
    exit();
}
?>
