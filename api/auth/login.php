<?php
require_once "../../autenticacion/conexion.php";
require_once "../helpers/jwt_helper.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"] ?? "";
$pass = $data["contraseña"] ?? "";

if (!$email || !$pass) {
    http_response_code(400);
    echo json_encode(["success" => false, "mensaje" => "Faltan datos"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
    if ($usuario["activo"] != 1) {
        echo json_encode(["success" => false, "mensaje" => "Cuenta no activada"]);
        exit;
    }

    if (password_verify($pass, $usuario["contraseña"])) {
        $token = generarJWT($usuario["email"]);
        echo json_encode([
            "success" => true,
            "mensaje" => "Login correcto",
            "token" => $token
        ]);
        exit;
    }
}

http_response_code(401);
echo json_encode(["success" => false, "mensaje" => "Credenciales incorrectas"]);
exit;
?>

