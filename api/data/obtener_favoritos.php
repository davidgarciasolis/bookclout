<?php
require 'conexion.php';
header('Content-Type: application/json');

$email = $_GET['email'] ?? '';

if (!$email) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT isbn FROM favoritos WHERE email_usuario = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$favoritos = [];
while ($row = $result->fetch_assoc()) {
    $favoritos[] = $row['isbn'];
}

echo json_encode($favoritos);
