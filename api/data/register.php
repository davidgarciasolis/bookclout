<?php
require_once "../../autenticacion/conexion.php"; // conexión a la base de datos
require '../../vendor/autoload.php';
require '../../vendor/smtp_config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->usuario) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos"]);
    exit;
}

$usuario = trim($data->usuario);
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32)); // Genera token de activación
$activo = 0; // Usuario no activo por defecto

// Validar si ya existe
$stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["mensaje" => "El usuario ya existe"]);
    exit;
}

// Insertar nuevo usuario con token y estado de activación
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, activo, token_activacion) VALUES (?, ?, ?, ?, ?)");
$result = $stmt->execute([$usuario, $email, $password, $activo, $token]);

if ($result) {
    // Enviar correo al usuario con el token de activación
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        configurarSMTP($mail);

        // Configuración del correo
        $mail->setFrom('bookcloud.no.reply@gmail.com', 'Bookcloud'); // Cambiar por tu correo y nombre
        $mail->addAddress($email, $usuario);
        $mail->isHTML(true);
        $mail->Subject = 'Activa tu cuenta en Bookcloud';

        $enlace = 'https://bookcloud.es/activar.php?token=' . $token; // Cambiar por tu dominio real
        $mail->Body = "
            <h1>Hola $usuario!</h1>
            <p>Gracias por registrarte. Haz clic en el siguiente enlace para activar tu cuenta:</p>
            <a href='$enlace'>$enlace</a>
            <p>Si no has solicitado este registro, puedes ignorar este mensaje.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $mail->ErrorInfo);
    }

    echo json_encode(["mensaje" => "Usuario registrado correctamente. Revisa tu correo para activar tu cuenta."]);
} else {
    http_response_code(500);
    echo json_encode(["mensaje" => "Error al registrar usuario"]);
}
