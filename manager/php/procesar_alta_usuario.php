<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

            // Enviar correo al usuario
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Cambiar por el host SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'bookcloud.no.reply@gmail.com'; // Cambiar por tu correo
                $mail->Password = 'hzir heql ozut zpht'; // Cambiar por tu contraseña
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->SMTPDebug = 2; // Habilitar el modo de depuración para obtener más detalles
                $mail->Debugoutput = 'error_log'; // Registrar la salida en el log de errores

                // Configuración del correo
                $mail->setFrom('bookcloud.no.reply@gmail.com', 'Bookcloud'); // Cambiar por tu correo y nombre
                $mail->addAddress($email, $nombre);
                $mail->isHTML(true);
                $mail->Subject = 'Bienvenido a nuestra plataforma';
                $mail->Body = '<h1>Hola ' . $nombre . '!</h1><p>Tu cuenta ha sido creada exitosamente.</p>';

                $mail->send();
            } catch (Exception $e) {
                error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            }
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


