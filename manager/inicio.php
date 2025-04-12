<?php 
// Comprueba que la sesión está activa
include '../autenticacion/check_sesion.php'; 
require '../autenticacion/conexion.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Resumen</h1>
        <?php

        // Consulta para obtener reservas activas
        $emailUsuario = $_SESSION['email'];
        $sqlReservas = "SELECT COUNT(*) AS total_reservas 
                        FROM reservas 
                        WHERE estado = 'activa'";
        $resultReservas = $conn->query($sqlReservas);
        $reservasActivas = ($resultReservas->num_rows > 0) ? $resultReservas->fetch_assoc()["total_reservas"] : 0;

        // Consulta para obtener préstamos no devueltos
        $sqlPrestamos = "SELECT COUNT(*) AS total_prestamos 
                         FROM prestamos 
                         WHERE fecha_devolucion IS NULL";
        $resultPrestamos = $conn->query($sqlPrestamos);
        $prestamosNoDevueltos = ($resultPrestamos->num_rows > 0) ? $resultPrestamos->fetch_assoc()["total_prestamos"] : 0;

        // Mostrar resumen
        echo"<p><strong>Reservas Activas:</strong> $reservasActivas</p>
            <p><strong>Préstamos No Devueltos:</strong> $prestamosNoDevueltos</p>";
        ?>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <?php
    // Cierra la conexión a la base de datos
    $conn->close();
    ?>
</body>
</html>
