<?php
// Inicia la sesión para manejar mensajes de error o éxito
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <form action="php/procesar_registro.php" method="POST">
        <h1>Registro de Usuario</h1>

        <!-- Mostrar mensajes de error o éxito -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p style='color:green'>" . $_SESSION['mensaje'] . "</p>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" maxlength="255" required>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" maxlength="255" required>

        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
