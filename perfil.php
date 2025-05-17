<?php
// Inicia la sesión para acceder a los datos del usuario
session_start();

// Incluye el archivo para verificar la sesión
include 'autenticacion/check_sesion.php';

// Recupera el email del usuario desde la sesión
$email = $_SESSION['email'];

// Realiza una consulta para obtener el nombre del usuario
include 'autenticacion/conexion.php';
$query = "SELECT nombre FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
} else {
    echo "<p>Error: No se pudo recuperar el nombre del usuario.</p>";
    exit();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Perfil de Usuario</h1>

        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <form action="php/cambiar_nombre.php" method="POST">
            <label for="nuevo_nombre">Nuevo Nombre:</label>
            <input type="text" id="nuevo_nombre" name="nuevo_nombre" maxlength="100" required>
            <button type="submit">Cambiar Nombre</button>
        </form>

        <form action="php/cambiar_contraseña.php" method="POST">
            <label for="nueva_contraseña">Nueva Contraseña:</label>
            <input type="password" id="nueva_contraseña" name="nueva_contraseña" maxlength="255" required>
            <button type="submit">Cambiar Contraseña</button>
        </form>

        <form action="autenticacion/logout_user.php" method="POST">
            <button type="submit">Cerrar Sesión</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
