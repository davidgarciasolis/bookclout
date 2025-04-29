<?php
require_once "../auth/validate.php";  // Protege con JWT
require_once "../../autenticacion/conexion.php";

header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM libros");
$libros = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($libros);
?>
