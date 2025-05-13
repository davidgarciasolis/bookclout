<?php
require '../autenticacion/conexion.php';
session_start();

try {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT id, nombre, contraseña, admin FROM usuarios WHERE email = ? AND activo = 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($contraseña, $usuario['contraseña'])) {
                // Inicia sesión y almacena los datos del usuario
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_admin'] = $usuario['admin'];

                // Redirige al usuario según su rol
                if ($usuario['admin'] == 1) {
                    header("Location: ../manager/inicio.php");
                } else {
                    header("Location: ../catalogo.php");
                }
                exit;
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado o cuenta inactiva.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error al preparar la consulta.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error en el sistema. Por favor, intenta más tarde.";
}

$conn->close();
header("Location: ../login.php");
exit;
?>