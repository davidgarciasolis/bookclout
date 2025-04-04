<?php include '../autenticacion/check_sesion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
        <!-- header -->
        <?php include 'header.php';?>
    
        <main>
            <h1>Alta de Usuario</h1>
            <form action="procesar_usuario.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required>
                <br><br>
                
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" maxlength="255" required>
                <br><br>
                
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" maxlength="255" required>
                <br><br>
                
                <label for="admin">¿Es administrador?</label>
                <input type="checkbox" id="admin" name="admin" value="1">
                <br><br>
                
                <button type="submit">Crear Usuario</button>
            </form>
        </main>

        <!-- footer -->
        <?php include '../footer.php';?>
</body>
</html>