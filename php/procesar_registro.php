<?php
require '../autenticacion/conexion.php';
session_start();

try {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $admin = 0; // Los usuarios registrados desde aquí no tienen permisos de administrador
    $token = bin2hex(random_bytes(32)); // Genera token de activación
    $activo = 0;

    $sql = "INSERT INTO usuarios (nombre, email, contraseña, admin, activo, token_activacion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssiss", $nombre, $email, $contraseña, $admin, $activo, $token);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro exitoso. Se ha enviado un correo de activación.";

            $mail = new PHPMailer(true);
            try {
                configurarSMTP($mail);
                $mail->setFrom('bookcloud.no.reply@gmail.com', 'Bookcloud');
                $mail->addAddress($email, $nombre);
                $mail->isHTML(true);
                $mail->Subject = 'Activa tu cuenta en Bookcloud';

                $enlace = 'https://bookcloud.es/activar.php?token=' . $token; // Cambia por tu dominio real
                $mail->Body = "
                    <h1>Hola $nombre!</h1>
                    <p>Gracias por registrarte. Haz clic en el siguiente enlace para activar tu cuenta:</p>
                    <a href='$enlace'>$enlace</a>
                    <p>Si no has solicitado este registro, puedes ignorar este mensaje.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            }
        } else {
            $_SESSION['mensaje'] = "El registro no se ha completado. Por favor, intenta nuevamente.";
        }

        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: El usuario ya existe o ha ocurrido un problema.";
}

$conn->close();
header("Location: ../registro.php");
exit;
?>
