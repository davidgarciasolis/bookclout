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

        // Fetch active reservations with book covers
        $query_reservas = "SELECT reservas.isbn, libros.titulo, libros.portada, reservas.fecha_reserva, reservas.estado FROM reservas JOIN libros ON reservas.isbn = libros.isbn WHERE reservas.email_usuario = ? AND reservas.estado = 'activa' ORDER BY reservas.fecha_reserva DESC";
        $stmt_reservas = $conn->prepare($query_reservas);
        $stmt_reservas->bind_param("s", $_SESSION['email']);
        $stmt_reservas->execute();
        $result_reservas = $stmt_reservas->get_result();

        echo "<section>";
        echo "<h2>Reservas Activas</h2>";
        if ($result_reservas->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_reservas->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Reserva:</strong> " . htmlspecialchars($row['fecha_reserva']) . "<br><strong>Estado:</strong> " . htmlspecialchars($row['estado']) . "</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes reservas activas.</p>";
        }
        echo "</section>";

        // Fetch active loans with book covers
        $query_prestamos = "SELECT prestamos.isbn, libros.titulo, libros.portada, prestamos.fecha_prestamo, prestamos.fecha_devolucion FROM prestamos JOIN libros ON prestamos.isbn = libros.isbn WHERE prestamos.email_usuario = ? AND prestamos.fecha_devolucion IS NULL ORDER BY prestamos.fecha_prestamo DESC";
        $stmt_prestamos = $conn->prepare($query_prestamos);
        $stmt_prestamos->bind_param("s", $_SESSION['email']);
        $stmt_prestamos->execute();
        $result_prestamos = $stmt_prestamos->get_result();

        echo "<section>";
        echo "<h2>Préstamos Activos</h2>";
        if ($result_prestamos->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_prestamos->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Préstamo:</strong> " . htmlspecialchars($row['fecha_prestamo']) . "<br><strong>Fecha de Devolución:</strong> " . htmlspecialchars($row['fecha_devolucion']) . "</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes préstamos activos.</p>";
        }
        echo "</section>";

        // Fetch historical reservations and loans
        echo "<section>";
        echo "<h2>Historial</h2>";

        $query_historial = "SELECT prestamos.isbn, libros.titulo, libros.portada, prestamos.fecha_prestamo FROM prestamos JOIN libros ON prestamos.isbn = libros.isbn WHERE prestamos.email_usuario = ? ORDER BY prestamos.fecha_prestamo DESC";
        $stmt_historial = $conn->prepare($query_historial);
        $stmt_historial->bind_param("s", $_SESSION['email']);
        $stmt_historial->execute();
        $result_historial = $stmt_historial->get_result();

        if ($result_historial->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_historial->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Préstamo:</strong> " . htmlspecialchars($row['fecha_prestamo']) . "</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes historial de préstamos.</p>";
        }

        echo "</section>";

        $conn->close();
        ?>
        <style>
            .book-item img {
                width: 75px;
                height: 100px;
                object-fit: cover;
            }
            .book-item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .book-item div {
                margin-left: 15px;
            }
        </style>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>