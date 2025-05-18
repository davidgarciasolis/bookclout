<?php
session_start();
include '../../autenticacion/check_sesion.php';
include '../../autenticacion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_nombre = trim($_POST['nuevo_nombre']);
    $email = $_SESSION['email'];

    if (!empty($nuevo_nombre)) {
        $query = "UPDATE usuarios SET nombre = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nuevo_nombre, $email);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Nombre actualizado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el nombre.";
        }

        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "El nombre no puede estar vacío.";
    }

    $conn->close();
    header("Location: ../perfil.php");
    exit();
}
?>
