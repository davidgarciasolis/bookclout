<?php
session_start(); // Se inicia sesion para comprobar el error
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <!-- formulario de login -->
     <form action="php/procesar_login.php" method="POST">
        <img src="img/favicon.png" alt="Logo">
        <h1>Iniciar sesion</h1>

        <!-- Error de inicio de session -->
        <?php
            if (isset($_SESSION["error"])) {
                echo "<p style='color:red'>" . $_SESSION["error"] . "</p>";
                unset($_SESSION["error"]);
            }
        ?>

        <label for="email">Email:</label>
        <input type="text" name="email" required>
        <label for="contraseña">Contraseña</label>
        <input type="password" name="contraseña" required>
        <button type="submit">Iniciar sesion</button>
        <label style="margin: 10px 0;">¿No tienes cuenta?</label>
        <button style="margin-top: 10px;" onclick="window.location.href='registro.php'">Crear una cuenta</button>
    </form>
</body>
</html>