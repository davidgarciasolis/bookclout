<?php
header("Content-Type: application/json");

require_once "../../autenticacion/conexion.php";
require_once "../helpers/jwt_helper.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? "";
$contraseña = $data["contraseña"] ?? "";

$stmt = $conn->prepare("SELECT email, contraseña FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($contraseña, $row["contraseña"])) {
        echo json_encode(["token" => generarJWT($row["email"])]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>
