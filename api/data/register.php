<?php
require_once "../../autenticacion/conexion.php"; // conexión a la base de datos
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->usuario) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos"]);
    exit;
}

$usuario = trim($data->usuario);
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);

// Validar si ya existe
$stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["mensaje" => "El usuario ya existe"]);
    exit;
}

// Insertar nuevo usuario
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
$result = $stmt->execute([$usuario, $email, $password]);

if ($result) {
    echo json_encode(["mensaje" => "Usuario registrado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["mensaje" => "Error al registrar usuario"]);
}
