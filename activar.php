<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar Cuenta</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .activation-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
        }
        .activation-message {
            padding: 40px; /* Incremento del tamaño del mensaje */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Aumentar el ancho máximo */
            margin: 30px auto; /* Más espacio alrededor */
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .activation-message h2 {
            margin-bottom: 10px;
        }
        .activation-message p {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color:green;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<main class="activation-container">
    <?php
    require 'autenticacion/conexion.php';

    $token = $_GET['token'] ?? '';

    if ($token) {
        $stmt = $conn->prepare("SELECT email FROM usuarios WHERE token_activacion = ? AND activo = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $stmt = $conn->prepare("UPDATE usuarios SET activo = 1, token_activacion = NULL WHERE token_activacion = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            echo '<div class="activation-message success">';
            echo '<h2>✅ Cuenta Activada</h2>';
            echo '<p>Tu cuenta ha sido activada exitosamente. Ya puedes iniciar sesión.</p>';
            echo '<a href="login.php" class="btn">Iniciar Sesión</a>';
            echo '</div>';
        } else {
            echo '<div class="activation-message error">';
            echo '<h2>❌ Error</h2>';
            echo '<p>Token inválido o la cuenta ya ha sido activada.</p>';
            echo '<a href="index.php" class="btn">Volver al Inicio</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="activation-message error">';
        echo '<h2>❌ Error</h2>';
        echo '<p>No se proporcionó un token válido.</p>';
        echo '<a href="index.php" class="btn">Volver al Inicio</a>';
        echo '</div>';
    }
    ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
