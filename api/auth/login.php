<?php
header("Content-Type: application/json");

require_once "../../autenticacion/conexion.php";
require_once "../helpers/jwt_helper.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? "";
$contraseña = $data["contraseña"] ?? "";

// Prepara una consulta SQL para buscar la contraseña y el estado activo del usuario en la base de datos
$stmt = $conn->prepare("SELECT email, contraseña, activo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email); // Vincula el parámetro del nombre de usuario a la consulta
$stmt->execute(); // Ejecuta la consulta
$result = $stmt->get_result(); // Obtiene el resultado de la consulta

if ($row = $result->fetch_assoc()) {
    // Verifica si el usuario está activo
    if ($row["activo"] == 1) {
        // Verifica si la contraseña proporcionada coincide con la contraseña encriptada almacenada
        if (password_verify($contraseña, $row["contraseña"])) {
            echo json_encode(["token" => generarJWT($row["email"])]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Contraseña incorrecta"]);
        }
    } else {
        http_response_code(403);
        echo json_encode(["error" => "La cuenta no está activa. Por favor, contacte al administrador."]);
    }
} else {
    http_response_code(401);
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>
