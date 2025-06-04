<?php
require '../autenticacion/conexion.php';
// Start session
session_start();

// Include the database connection
include '../autenticacion/conexion.php';

// Check if id_reserva is provided
if (!isset($_GET['id_reserva'])) {
    echo "Error: ID de reserva no proporcionado.";
    exit();
}

$id_reserva = $_GET['id_reserva'];

// Prepare the SQL statement to cancel the reservation
$query_cancelar = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = ? AND email_usuario = ?";
$stmt_cancelar = $conn->prepare($query_cancelar);
$stmt_cancelar->bind_param("is", $id_reserva, $_SESSION['email']);

// Add feedback to the user about the cancellation status
if ($stmt_cancelar->execute()) {
    $_SESSION['cancel_status'] = 'success';
} else {
    $_SESSION['cancel_status'] = 'error';
}

header("Location: ../reservas_prestamos.php");
exit();

$stmt_cancelar->close();
$conn->close();
?>
