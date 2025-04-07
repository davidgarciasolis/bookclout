<?php 
// Verifica si el usuario tiene una sesión activa
require '../autenticacion/check_sesion.php'; 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos</title>
    
    <link rel="stylesheet" href="../css/styles.css">
    
    <script>
        // Función para confirmar antes de eliminar un préstamo
        function confirmarEliminacion(idPrestamo, form) {
            if (confirm(`¿Estás seguro de que quieres eliminar el préstamo con ID ${idPrestamo}?`)) {
                form.submit();
            }
        }

        // Ocultar o mostrar el botón "Entregar" dependiendo de la fecha de devolución
        function actualizarVisibilidadBotonEntregar() {
            const filas = document.querySelectorAll('table tr');
            
            filas.forEach(fila => {
                const celdaFechaDevolucion = fila.querySelector('td:nth-child(5)');
                const formEntregar = fila.querySelector('form[action="php/procesar_entrega_prestamo.php"]');

                if (celdaFechaDevolucion && formEntregar) {
                    const fechaDevolucion = celdaFechaDevolucion.textContent.trim();
                    // Mostrar el botón solo si la fecha de devolución está vacía o es "No devuelto"
                    if (!fechaDevolucion || fechaDevolucion === 'No devuelto') {
                        formEntregar.style.display = 'block';
                    } else {
                        formEntregar.style.display = 'none';
                    }
                }
            });
        }

        // Llamar la función al cargar la página
        document.addEventListener('DOMContentLoaded', actualizarVisibilidadBotonEntregar);
    </script>
</head>
<body>
    <?php include 'includes/header.php';?>

    <main>
        <?php
        // Mensaje de notificación para el usuario
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <h1>
            Préstamos
            <a href="alta_prestamo.php"><button>Agregar Préstamo</button></a>
        </h1>

        <?php
        require '../autenticacion/conexion.php';

        // Consulta SQL para obtener los préstamos actuales
        $sql = "SELECT p.id_prestamo, l.titulo AS libro, email AS usuario, p.fecha_prestamo, p.fecha_devolucion
                FROM prestamos p
                JOIN libros l ON p.isbn = l.isbn
                JOIN usuarios u ON p.email_usuario = u.email"; 
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Libro</th><th>Usuario</th><th>Fecha Préstamo</th><th>Fecha Devolución</th><th>Opciones</th></tr>";
            while($row = $result->fetch_assoc()) {
                $idPrestamo = htmlspecialchars($row["id_prestamo"]);
                $tituloLibro = htmlspecialchars($row["libro"]);
                $nombreUsuario = htmlspecialchars($row["usuario"]);
                $fechaPrestamo = htmlspecialchars($row["fecha_prestamo"]);
                $fechaDevolucion = htmlspecialchars($row["fecha_devolucion"] ?? 'No devuelto');

                echo "<tr>";
                echo "<td>$idPrestamo</td>";
                echo "<td>$tituloLibro</td>";
                echo "<td>$nombreUsuario</td>";
                echo "<td>$fechaPrestamo</td>";
                echo "<td>$fechaDevolucion</td>";
                echo "<td>
                     <form action='php/procesar_entrega_prestamo.php' method='POST'>
                        <input type='hidden' name='id_prestamo' value='$idPrestamo'>
                        <button type='submit'>Entregar</button>
                    </form>
                    <form action='php/procesar_eliminar_prestamo.php' method='POST' onsubmit='event.preventDefault(); confirmarEliminacion(\"$idPrestamo\", this);'>
                        <input type='hidden' name='id_prestamo' value='$idPrestamo'>
                        <button type='submit'>Eliminar</button>
                    </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay préstamos registrados.</p>";
        }

        $conn->close();
        ?>
    </main>

    <?php include 'includes/footer.php';?>
</body>
</html>
