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
        $query_reservas = "SELECT reservas.id_reserva, reservas.isbn, libros.titulo, libros.portada, reservas.fecha_reserva, reservas.estado FROM reservas JOIN libros ON reservas.isbn = libros.isbn WHERE reservas.email_usuario = ? AND reservas.estado = 'activa' ORDER BY reservas.fecha_reserva DESC";
        $stmt_reservas = $conn->prepare($query_reservas);
        $stmt_reservas->bind_param("s", $_SESSION['email']);
        $stmt_reservas->execute();
        $result_reservas = $stmt_reservas->get_result();

        echo "<section style='text-align: left;'>";
        echo "<h2>Reservas Activas</h2>";
        if ($result_reservas->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_reservas->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>ID Reserva:</strong> " . htmlspecialchars($row['id_reserva']) . "<br><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Reserva:</strong> " . htmlspecialchars($row['fecha_reserva']) . "<br><strong>Estado:</strong> " . htmlspecialchars($row['estado']) . "</div>";
                echo "<button class='cancelar-btn' onclick='confirmarCancelacion(\"" . $row['id_reserva'] . "\")'>Cancelar</button>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes reservas activas.</p>";
        }
        echo "</section>";

        // Fetch active loans with book covers (prestamos activos = fecha_devolucion IS NULL)
        $query_prestamos = "SELECT prestamos.isbn, libros.titulo, libros.portada, prestamos.fecha_prestamo, prestamos.fecha_devolucion FROM prestamos JOIN libros ON prestamos.isbn = libros.isbn WHERE prestamos.email_usuario = ? AND prestamos.fecha_devolucion IS NULL ORDER BY prestamos.fecha_prestamo DESC";
        $stmt_prestamos = $conn->prepare($query_prestamos);
        $stmt_prestamos->bind_param("s", $_SESSION['email']);
        $stmt_prestamos->execute();
        $result_prestamos = $stmt_prestamos->get_result();

        echo "<section style='text-align: left;'>";
        echo "<h2>Préstamos Activos</h2>";
        if ($result_prestamos->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_prestamos->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Préstamo:</strong> " . htmlspecialchars($row['fecha_prestamo']) . "<br><strong>Fecha de Devolución:</strong> Pendiente</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes préstamos activos.</p>";
        }
        echo "</section>";

        // Fetch historical reservations and loans
        echo "<section style='text-align: left;'>";
        echo "<h2>Historial</h2>";

        // Solo mostrar préstamos que ya fueron devueltos (fecha_devolucion NO es NULL)
        $query_historial = "SELECT prestamos.isbn, libros.titulo, libros.portada, prestamos.fecha_prestamo, prestamos.fecha_devolucion FROM prestamos JOIN libros ON prestamos.isbn = libros.isbn WHERE prestamos.email_usuario = ? AND prestamos.fecha_devolucion IS NOT NULL ORDER BY prestamos.fecha_prestamo DESC";
        $stmt_historial = $conn->prepare($query_historial);
        $stmt_historial->bind_param("s", $_SESSION['email']);
        $stmt_historial->execute();
        $result_historial = $stmt_historial->get_result();

        if ($result_historial->num_rows > 0) {
            echo "<ul style='list-style-type: none; padding: 0;'>";
            while ($row = $result_historial->fetch_assoc()) {
                echo "<li class='book-item'>";
                echo "<img src='" . htmlspecialchars($row['portada']) . "' alt='Portada de " . htmlspecialchars($row['titulo']) . "'>";
                echo "<div><strong>Título:</strong> " . htmlspecialchars($row['titulo']) . "<br><strong>Fecha de Préstamo:</strong> " . htmlspecialchars($row['fecha_prestamo']) . "<br><strong>Fecha de Devolución:</strong> " . htmlspecialchars($row['fecha_devolucion']) . "</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes historial de préstamos.</p>";
        }

        echo "</section>";

        // Display feedback message for cancellation status
        if (isset($_SESSION['cancel_status'])) {
            if ($_SESSION['cancel_status'] === 'success') {
                echo "<p style='color: green;'>La reserva se ha cancelado correctamente.</p>";
            } elseif ($_SESSION['cancel_status'] === 'error') {
                echo "<p style='color: red;'>Hubo un error al cancelar la reserva. Por favor, inténtalo de nuevo.</p>";
            }
            unset($_SESSION['cancel_status']); // Clear the status after displaying
        }

        $conn->close();
        ?>

    </main>

    <!-- Modal de confirmación -->
    <div id="modal-cancelar" class="modal" style="display:none;">
        <div class="modal-content">
            <img src="img/zorro_asustado.png" alt="Zorro asustado" style="width: 100px; margin-bottom: 10px;">
            <p>¿Estás seguro de que quieres cancelar esta reserva?</p>
            <div class="modal-actions">
                <button onclick="cancelarReserva()" class="confirmar">Sí, cancelar</button>
                <button onclick="cerrarModal()" class="cancelar">No</button>
            </div>
        </div>
    </div>

    <script>
        let idReservaAEliminar = null;

        function confirmarCancelacion(id_reserva) {
            idReservaAEliminar = id_reserva;
            document.getElementById('modal-cancelar').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modal-cancelar').style.display = 'none';
            idReservaAEliminar = null;
        }

        function cancelarReserva() {
            if (idReservaAEliminar) {
                window.location.href = 'php/procesar_cancelar_reserva.php?id_reserva=' + encodeURIComponent(idReservaAEliminar);
            }
        }
    </script>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
