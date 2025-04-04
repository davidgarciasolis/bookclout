<?php 
// Incluye un archivo para verificar que el usuario tiene una sesión activa
include '../autenticacion/check_sesion.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Especifica el conjunto de caracteres y escala la vista para dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Crear usuario</title>
    <!-- Enlace al archivo CSS para estilos -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
        <!-- header: Incluye el encabezado común desde un archivo externo -->
        <?php include 'includes/header.php';?>

        <main>
            <?php
            // Comprobar si existe un mensaje en la sesión para mostrar al usuario
            if (isset($_SESSION['mensaje'])) {
                $mensaje = $_SESSION['mensaje']; // Almacena el mensaje de la sesión
                $tipo = $_SESSION['mensaje_tipo']; // Almacena el tipo de mensaje (éxito o error)
                
                // Mostrar el mensaje según el tipo
                if ($tipo == 'exito') {
                    echo "<script>alert('" . $mensaje . "');</script>"; // Mensaje de éxito
                } elseif ($tipo == 'error') {
                    echo "<script>alert('" . $mensaje . "');</script>"; // Mensaje de error
                }

                // Limpiar el mensaje de la sesión después de mostrarlo
                unset($_SESSION['mensaje']);
                unset($_SESSION['mensaje_tipo']);
            }
            ?>
            <!-- Formulario para crear un nuevo usuario -->
            <form action="php/procesar_alta_usuario.php" method="POST">
                <!-- Título principal de la página -->
                <h1>Alta de Usuario</h1>
                <!-- Campo para el nombre del usuario -->
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required>
                <br><br>
                
                <!-- Campo para el correo electrónico del usuario -->
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" maxlength="255" required>
                <br><br>
                
                <!-- Campo para la contraseña del usuario -->
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" maxlength="255" required>
                <br><br>
                
                <!-- Checkbox para indicar si el usuario es administrador -->
                <label for="admin">¿Es administrador?</label>
                <input type="checkbox" id="admin" name="admin" value="1">
                <br><br>
                
                <!-- Botón para enviar el formulario -->
                <button type="submit">Crear Usuario</button>
            </form>
        </main>

        <!-- footer: Incluye el pie de página común desde un archivo externo -->
        <?php include 'includes/footer.php';?>
</body>
</html>
