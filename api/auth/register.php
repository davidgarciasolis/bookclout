<?php
require_once '../helpers/db.php'; //base de datos

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->usuario) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos"]);
    exit;
}

$usuario = trim($data->usuario);
$password = password_hash($data->password, PASSWORD_DEFAULT);

//Por si ya existe el usuario
$stmt = $db->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["mensaje" => "El usuario ya existe"]);
    exit;
}

// Poner un nuevo usuario
$stmt = $db->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
$result = $stmt->execute([$usuario, $password]);

if ($result) {
    echo json_encode(["mensaje" => "Usuario creado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["mensaje" => "Error al crear el usuario"]);
}
