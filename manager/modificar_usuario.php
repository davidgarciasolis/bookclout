<?php
// Incluye el archivo que verifica la sesión del usuario
require '../autenticacion/check_sesion.php';

// Incluye la conexión a la base de datos
require '../autenticacion/conexion.php';

// Verifica si se recibió el correo del usuario a editar
if (isset($_POST['email'])) {
    $emailUsuario = htmlspecialchars($_POST['email']);

    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT nombre, email, admin FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $emailUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        } else {
            $_SESSION['mensaje'] = "Usuario no encontrado.";
            header('Location: usuarios.php');
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
        header('Location: usuarios.php');
        exit;
    }
} else {
    $_SESSION['mensaje'] = "No se especificó un usuario.";
    header('Location: usuarios.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'includes/header.php';?>

    <main>
        <form action="php/procesar_modificar_usuario.php" method="POST">
            <h1>Modificar Usuario: <?php echo htmlspecialchars($_POST['email']); ?></h1>
            <input type="hidden" name="email_actual" value="<?php echo htmlspecialchars($usuario['email']); ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            
            <label for="admin">¿Es administrador? <input type="checkbox" name="admin" id="admin" <?php echo $usuario['admin'] ? 'checked' : ''; ?>></label>
            <br><br>

            <button type="submit">Actualizar Usuario</button>
        </form>
    </main>

    <?php include 'includes/footer.php';?>
</body>
</html>
