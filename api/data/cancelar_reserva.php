<?php
require_once "../auth/validate.php";
require_once "../../autenticacion/conexion.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$idReserva = $data["id_reserva"] ?? null;
$email = $decoded->userId;

if (!$idReserva) {
    http_response_code(400);
    echo json_encode(["error" => "id_reserva requerido"]);
    exit;
}

// Verifica que la reserva exista, sea del usuario y esté activa
$stmt = $conn->prepare("SELECT id_reserva FROM reservas WHERE id_reserva = ? AND email_usuario = ? AND estado = 'activa'");
$stmt->bind_param("is", $idReserva, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Reserva no encontrada o ya cancelada"]);
    exit;
}

// Actualiza el estado a cancelada
$stmt = $conn->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = ?");
$stmt->bind_param("i", $idReserva);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Reserva cancelada correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al cancelar la reserva"]);
}
