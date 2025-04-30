<?php

require_once "../auth/validate.php";
require_once "../../autenticacion/conexion.php";
header("Content-Type: application/json");

// Verificar si el email está definido en el token
if (!isset($decoded->userId)) {
    http_response_code(400);
    echo json_encode(["error" => "Email no definido en el token"]);
    exit;
}

$email = $decoded->userId;

// Cambiar el tipo de dato en bind_param a "s" para strings
$stmt = $conn->prepare("SELECT id_reserva, isbn, fecha_reserva, fecha_expiracion, estado FROM reservas WHERE email_usuario = ? ORDER BY fecha_reserva DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$reservas = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($reservas);

?>