<?php
require 'autenticacion/conexion.php';

$token = $_GET['token'] ?? '';

if ($token) {
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE token_activacion = ? AND activo = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $stmt = $conn->prepare("UPDATE usuarios SET activo = 1, token_activacion = NULL WHERE token_activacion = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        echo "✅ Tu cuenta ha sido activada. Ya puedes iniciar sesión.";
    } else {
        echo "❌ Token inválido o cuenta ya activada.";
    }
} else {
    echo "❌ Token no proporcionado.";
}
?>
