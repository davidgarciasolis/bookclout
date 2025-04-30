<?php
require_once "../helpers/jwt_helper.php";

$headers = apache_request_headers();
$auth = $headers["Authorization"] ?? "";

if (!$auth || !str_starts_with($auth, "Bearer ")) {
    http_response_code(401);
    echo json_encode(["error" => "Token no proporcionado"]);
    exit;
}

$jwt = str_replace("Bearer ", "", $auth);
$decoded = validarJWT($jwt);

if (!$decoded) {
    http_response_code(401);
    echo json_encode(["error" => "Token inválido o expirado"]);
    exit;
}

// Ahora puedes usar $decoded->userId o $decoded->email
?>
