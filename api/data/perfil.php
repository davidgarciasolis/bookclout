<?php
require_once "../auth/validate.php";
require_once "../../autenticacion/conexion.php";

header("Content-Type: application/json");

if (!isset($decoded->userId)) {
    http_response_code(401);
    echo json_encode(["error" => "Token inválido"]);
    exit;
}

$email = $decoded->userId;
$stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["usuario" => $row["usuario"]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Usuario no encontrado"]);
}
$conn->close();
