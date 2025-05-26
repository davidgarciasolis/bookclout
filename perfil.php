<?php
// Inicia la sesión para acceder a los datos del usuario
session_start();

// Incluye el archivo para verificar la sesión
include 'autenticacion/check_sesion_no_admin.php';

// Recupera el email del usuario desde la sesión
$email = $_SESSION['email'];

// Realiza una consulta para obtener el nombre del usuario
include '../autenticacion/conexion.php';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        main {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        main h1 {
            text-align: center;
            color: #333;
        }
        main p {
            font-size: 1.1em;
            color: #555;
        }
        main form {
            all: unset;
            display: block;
        }
        main form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        main form input[type="text"], main form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        main form button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        main form button:hover {
            background-color: darkgreen;
        }
    </style>
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
        <br>
        <form action="../autenticacion/logout_manager.php" method="POST">
            <button type="submit">Cerrar Sesión</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
