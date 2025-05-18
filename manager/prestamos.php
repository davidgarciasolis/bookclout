<?php 
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
        // Variables para el estado de ordenación
        let columnaOrdenada = -1;
        let ordenAscendente = true;

        // Confirmación antes de eliminar un préstamo
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
                    if (!fechaDevolucion || fechaDevolucion === 'No devuelto') {
                        formEntregar.style.display = 'block';
                    } else {
                        formEntregar.style.display = 'none';
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', actualizarVisibilidadBotonEntregar);

        // Función para filtrar la tabla
        function filtrarTabla() {
            let filtro = document.getElementById("buscar").value.toLowerCase();
            let filas = document.querySelectorAll("table tr:not(:first-child)");

            filas.forEach(fila => {
                let textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? "" : "none";
            });
        }

        // Función para ordenar columnas de la tabla
        function ordenarTabla(indiceColumna) {
            let tabla = document.getElementById("tablaPrestamos");
            let filas = Array.from(tabla.rows).slice(1); // Ignorar encabezado

            if (columnaOrdenada === indiceColumna) {
                ordenAscendente = !ordenAscendente;
            } else {
                columnaOrdenada = indiceColumna;
                ordenAscendente = true;
            }

            filas.sort((a, b) => {
                let celdaA = a.cells[indiceColumna].textContent.trim().toLowerCase();
                let celdaB = b.cells[indiceColumna].textContent.trim().toLowerCase();
                if (celdaA < celdaB) {
                    return ordenAscendente ? -1 : 1;
                }
                if (celdaA > celdaB) {
                    return ordenAscendente ? 1 : -1;
                }
                return 0;
            });

            filas.forEach(fila => tabla.appendChild(fila)); // Rearrancar filas
        }
    </script>
</head>
<body>
    <?php include 'includes/header.php';?>

    <main>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <h1>
            Préstamos
        </h1>
        <h1>
            <!-- Barra de búsqueda -->
            <input type="text" id="buscar" placeholder="Buscar en la tabla..." onkeyup="filtrarTabla()">
            <a href="alta_prestamo.php"><button>Agregar Préstamo</button></a>
        </h1>

        <?php
        require '../autenticacion/conexion.php';

        $sql = "SELECT p.id_prestamo, l.titulo AS libro, email AS usuario, p.fecha_prestamo, p.fecha_devolucion
                FROM prestamos p
                JOIN libros l ON p.isbn = l.isbn
                JOIN usuarios u ON p.email_usuario = u.email
                ORDER BY CASE WHEN p.fecha_devolucion IS NULL THEN 1 ELSE 0 END DESC, p.fecha_devolucion DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' id='tablaPrestamos'>";
            echo "<tr>
                <th onclick='ordenarTabla(0)'>ID</th>
                <th onclick='ordenarTabla(1)'>Libro</th>
                <th onclick='ordenarTabla(2)'>Usuario</th>
                <th onclick='ordenarTabla(3)'>Fecha Préstamo</th>
                <th onclick='ordenarTabla(4)'>Fecha Devolución</th>
                <th>Opciones</th>
            </tr>";
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
