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

$stmt = $conn->prepare("SELECT * FROM prestamos WHERE email_usuario = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$prestamos = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($prestamos);

?>