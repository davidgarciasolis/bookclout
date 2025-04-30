<?php
require_once "../auth/validate.php";
require_once "../../autenticacion/conexion.php";
header("Content-Type: application/json");

// Recibir datos desde la app
$data = json_decode(file_get_contents("php://input"), true);
$isbn = $data["isbn"] ?? null;
$email = $decoded->userId; // Asegúrate de guardar el email en el token JWT

if (!$isbn) {
    http_response_code(400);
    echo json_encode(["error" => "isbn requerido"]);
    exit;
}

// Verificar si ya tiene una reserva activa para ese libro
$stmt = $conn->prepare("SELECT id_reserva FROM reservas WHERE email_usuario = ? AND isbn = ? AND estado = 'activa'");
$stmt->bind_param("ss", $email, $isbn);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409); // Conflicto
    echo json_encode(["error" => "Ya tienes una reserva activa para este libro"]);
    exit;
}

// Calcular fecha de expiración (3 días desde ahora)
$fechaExpiracion = date("Y-m-d H:i:s", strtotime("+3 days"));

// Insertar nueva reserva
$stmt = $conn->prepare("INSERT INTO reservas (isbn, email_usuario, fecha_reserva, fecha_expiracion, estado) VALUES (?, ?, NOW(), ?, 'activa')");
$stmt->bind_param("sss", $isbn, $email, $fechaExpiracion);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Reserva realizada correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar la reserva"]);
}
?>