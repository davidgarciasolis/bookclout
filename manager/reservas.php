<?php 
// Verificar sesión activa
require '../autenticacion/check_sesion.php'; 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    
    <link rel="stylesheet" href="../css/styles.css">
    
    <script>
        // Confirmación para cancelar una reserva
        function confirmarCancelacion(tituloLibro, form) {
            if (confirm(`¿Estás seguro de que quieres cancelar la reserva del libro ${tituloLibro}?`)) {
                form.submit();
            }
        }

        // Confirmación para prestar un libro
        function confirmarPrestamo(tituloLibro, form) {
            if (confirm(`¿Quieres convertir la reserva del libro ${tituloLibro} en un préstamo?`)) {
                form.submit();
            }
        }
    </script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <h1>
            Reservas
        </h1>

        <?php
        require '../autenticacion/conexion.php';

        $emailUsuario = $_SESSION['email'];
        $sql = "SELECT r.id_reserva, l.titulo, r.fecha_reserva, r.fecha_expiracion, r.estado 
                FROM reservas r
                JOIN libros l ON r.isbn = l.isbn
                WHERE r.email_usuario = '$emailUsuario'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Título del Libro</th><th>Fecha de Reserva</th><th>Fecha de Expiración</th><th>Estado</th><th>Opciones</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $tituloLibro = htmlspecialchars($row["titulo"]);
                $fechaReserva = htmlspecialchars($row["fecha_reserva"]);
                $fechaExpiracion = htmlspecialchars($row["fecha_expiracion"]);
                $estado = htmlspecialchars($row["estado"]);
                
                echo "<tr>";
                echo "<td>$tituloLibro</td>";
                echo "<td>$fechaReserva</td>";
                echo "<td>$fechaExpiracion</td>";
                echo "<td>$estado</td>";
                if ($estado === 'activa') {
                    echo "<td>
                         <form action='php/procesar_prestamo.php' method='POST' onsubmit='event.preventDefault(); confirmarPrestamo(\"$tituloLibro\", this);'>
                            <input type='hidden' name='id_reserva' value='" . htmlspecialchars($row["id_reserva"]) . "'>
                            <button type='submit'>Prestar</button>
                        </form>
                        <form action='php/procesar_cancelar_reserva.php' method='POST' onsubmit='event.preventDefault(); confirmarCancelacion(\"$tituloLibro\", this);'>
                            <input type='hidden' name='id_reserva' value='" . htmlspecialchars($row["id_reserva"]) . "'>
                            <button type='submit'>Cancelar</button>
                        </form>
                        </td>";
                } else {
                    echo "<td>Sin opciones disponibles</td>";
                }
                
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No tienes reservas activas.</p>";
        }

        $conn->close();
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

