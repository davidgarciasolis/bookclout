<?php
session_start();
include '../../autenticacion/check_sesion.php';
include '../../autenticacion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_contraseña = trim($_POST['nueva_contraseña']);
    $email = $_SESSION['email'];

    if (!empty($nueva_contraseña)) {
        $hashed_password = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET contraseña = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Contraseña actualizada correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar la contraseña.";
        }

        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "La contraseña no puede estar vacía.";
    }

    $conn->close();
    header("Location: ../perfil.php");
    exit();
}
?>
