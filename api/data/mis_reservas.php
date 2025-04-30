<?php
require_once "../auth/validate.php";
require_once "../../autenticacion/conexion.php";
header("Content-Type: application/json");

$email = $decoded->userId;

// Si quieres ver solo las activas, descomenta la línea con el filtro
$stmt = $conn->prepare("
    SELECT id_reserva, isbn, fecha_reserva, fecha_expiracion, estado 
    FROM reservas 
    WHERE email_usuario = ? AND estado = 'activa' 
    ORDER BY fecha_reserva DESC
");
// $stmt = $mysqli->prepare("SELECT id_reserva, isbn, fecha_reserva, fecha_expiracion, estado FROM reservas WHERE email_usuario = ? AND estado = 'activa' ORDER BY fecha_reserva DESC");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$reservas = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($reservas);
?>