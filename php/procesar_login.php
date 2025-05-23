<?php
require '../autenticacion/conexion.php';
session_start();

try {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT email, nombre, contraseña, admin FROM usuarios WHERE email = ? AND activo = 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($contraseña, $usuario['contraseña'])) {
                // Inicia sesión y almacena los datos del usuario
                $_SESSION['usuario_id'] = $usuario['email'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_admin'] = $usuario['admin'];

                $_SESSION["email"] = $email; // Guarda el nombre de usuario en la sesión
                header("Location: ../index.php"); // Redirige al usuario a la página principal
                exit(); // Finaliza la ejecución del script
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