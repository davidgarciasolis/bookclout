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

// Validar si ya existe
$stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["mensaje" => "El usuario ya existe"]);
    exit;
}

// Insertar nuevo usuario
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
$result = $stmt->execute([$usuario, $email, $password]);

if ($result) {
    // Enviar correo al usuario
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        configurarSMTP($mail);

        // Configuración del correo
        $mail->setFrom('bookcloud.no.reply@gmail.com', 'Bookcloud'); // Cambiar por tu correo y nombre
        $mail->addAddress($email, $usuario);
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenido a nuestra plataforma';
        $mail->Body = '<h1>Hola ' . $usuario . '!</h1><p>Tu cuenta ha sido creada exitosamente.</p>';

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $mail->ErrorInfo);
    }

    echo json_encode(["mensaje" => "Usuario registrado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["mensaje" => "Error al registrar usuario"]);
}
