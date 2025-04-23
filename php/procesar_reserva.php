<?php
session_start();
include '../autenticacion/conexion.php';
include '../autenticacion/check_sesion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'])) {
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $email = $_SESSION['email'];
    $fecha_creacion = date('Y-m-d H:i:s');
    $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+7 days'));

    // Insert the reservation into the database
    $query = "INSERT INTO reservas (isbn, email_usuario, fecha_reserva, fecha_expiracion, estado) VALUES ('$isbn', '$email', '$fecha_creacion', '$fecha_expiracion', 'activa')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Reserva creada exitosamente.'); window.location.href='../../libro.php?isbn=$isbn';</script>";
    } else {
        echo "<script>alert('Error al crear la reserva: " . $conn->error . "'); window.location.href='../../libro.php?isbn=$isbn';</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Datos inválidos.'); window.location.href='../../index.php';</script>";
}
?>