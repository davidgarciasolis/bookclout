<?php
require 'conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$isbn = $data['isbn'] ?? '';
$accion = $data['accion'] ?? '';

if ($email && $isbn) {
    if ($accion === "añadir") {
        $stmt = $conn->prepare("REPLACE INTO favoritos (email_usuario, isbn) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $isbn);
    } elseif ($accion === "eliminar") {
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE email_usuario = ? AND isbn = ?");
        $stmt->bind_param("ss", $email, $isbn);
    } else {
        echo json_encode(["success" => false, "error" => "Acción inválida"]);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
}
