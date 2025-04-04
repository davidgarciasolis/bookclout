<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';

try {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $admin = isset($_POST['admin']) ? 1 : 0;

    // Preparar la consulta para insertar datos
    $sql = "INSERT INTO usuarios (nombre, email, contraseña, admin) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind de parámetros
        $stmt->bind_param("sssi", $nombre, $email, $contraseña, $admin);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario creado exitosamente.";
            $_SESSION['mensaje_tipo'] = "exito";
        } else {
            $_SESSION['mensaje'] = "El usuario no se ha creado. Por favor, intenta nuevamente.";
            $_SESSION['mensaje_tipo'] = "error";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['mensaje_tipo'] = "error";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: El usuario ya existe";
    $_SESSION['mensaje_tipo'] = "error";
}

// Cerrar la conexión
$conn->close();

// Redirigir a la página de alta de usuario
header("Location: ../alta_usuario.php");
exit;
?>


