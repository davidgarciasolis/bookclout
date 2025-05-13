<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';
require '../../vendor/autoload.php';
require '../../vendor/smtp_config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $admin = isset($_POST['admin']) ? 1 : 0;
    $token = bin2hex(random_bytes(32)); // Genera token de activación
    $activo = 0;

    $sql = "INSERT INTO usuarios (nombre, email, contraseña, admin, activo, token_activacion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssiss", $nombre, $email, $contraseña, $admin, $activo, $token);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario creado exitosamente. Se ha enviado un correo de activación.";
            $_SESSION['mensaje_tipo'] = "exito";

            $mail = new PHPMailer(true);
            try {
                configurarSMTP($mail);
                $mail->setFrom('bookcloud.no.reply@gmail.com', 'Bookcloud');
                $mail->addAddress($email, $nombre);
                $mail->isHTML(true);
                $mail->Subject = 'Activa tu cuenta en Bookcloud';

                $enlace = 'https://bookcloud.es/activar.php?token=' . $token; // <-- cambia por tu dominio real
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
            $_SESSION['mensaje'] = "El usuario no se ha creado. Por favor, intenta nuevamente.";
            $_SESSION['mensaje_tipo'] = "error";
        }

        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['mensaje_tipo'] = "error";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: El usuario ya existe";
    $_SESSION['mensaje_tipo'] = "error";
}

$conn->close();
header("Location: ../alta_usuario.php");
exit;
?>


