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

// Consulta para detalles del libro y que se pueda ver la ficha del libro en la app
$stmt = $conn->prepare("
    SELECT 
        r.id_reserva,
        r.isbn,
        r.fecha_reserva,
        r.fecha_expiracion,
        r.estado,
        l.titulo,
        l.autor,
        l.portada AS imagen
    FROM reservas r
    JOIN libros l ON r.isbn = l.isbn
    WHERE r.email_usuario = ?
    ORDER BY r.fecha_reserva DESC
");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$reservas = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($reservas);
?>
