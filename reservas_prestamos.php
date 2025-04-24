<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas y Préstamos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Mis Reservas y Préstamos</h1>

        <?php
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['email'])) {
            header("Location: login.php");
            exit();
        }

        // Include the database connection
        include 'autenticacion/conexion.php';

        // Fetch active reservations
        $query_reservas = "SELECT titulo, fecha_reserva, estado FROM reservas WHERE email_usuario = ? ORDER BY fecha_reserva DESC";
        $stmt_reservas = $conn->prepare($query_reservas);
        $stmt_reservas->bind_param("s", $_SESSION['email']);
        $stmt_reservas->execute();
        $result_reservas = $stmt_reservas->get_result();

        echo "<section>";
        echo "<h2>Reservas Activas</h2>";
        if ($result_reservas->num_rows > 0) {
            echo "<ul>";
            while ($row = $result_reservas->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['titulo']) . " - " . htmlspecialchars($row['fecha_reserva']) . " - Estado: " . htmlspecialchars($row['estado']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes reservas activas.</p>";
        }
        echo "</section>";

        // Fetch active loans
        $query_prestamos = "SELECT titulo, fecha_prestamo, fecha_devolucion FROM prestamos WHERE email_usuario = ? ORDER BY fecha_prestamo DESC";
        $stmt_prestamos = $conn->prepare($query_prestamos);
        $stmt_prestamos->bind_param("s", $_SESSION['email']);
        $stmt_prestamos->execute();
        $result_prestamos = $stmt_prestamos->get_result();

        echo "<section>";
        echo "<h2>Préstamos Activos</h2>";
        if ($result_prestamos->num_rows > 0) {
            echo "<ul>";
            while ($row = $result_prestamos->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['titulo']) . " - Fecha de Préstamo: " . htmlspecialchars($row['fecha_prestamo']) . " - Fecha de Devolución: " . htmlspecialchars($row['fecha_devolucion']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes préstamos activos.</p>";
        }
        echo "</section>";

        // Fetch historical reservations and loans
        echo "<section>";
        echo "<h2>Historial</h2>";

        $query_historial = "SELECT titulo, tipo, fecha FROM historial WHERE email_usuario = ? ORDER BY fecha DESC";
        $stmt_historial = $conn->prepare($query_historial);
        $stmt_historial->bind_param("s", $_SESSION['email']);
        $stmt_historial->execute();
        $result_historial = $stmt_historial->get_result();

        if ($result_historial->num_rows > 0) {
            echo "<ul>";
            while ($row = $result_historial->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['titulo']) . " - " . htmlspecialchars($row['tipo']) . " - Fecha: " . htmlspecialchars($row['fecha']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes historial de reservas o préstamos.</p>";
        }

        echo "</section>";

        $conn->close();
        ?>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>