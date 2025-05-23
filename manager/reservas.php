<?php 
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
        // Variables globales para el estado de ordenación
        let columnaOrdenada = -1;
        let ordenAscendente = true;

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

        // Función para filtrar la tabla
        function filtrarTabla() {
            let input = document.getElementById("buscar");
            let filtro = input.value.toLowerCase();
            let tabla = document.getElementById("tablaReservas");
            let filas = tabla.getElementsByTagName("tr");

            // Iterar sobre las filas de la tabla, omitiendo la fila de encabezado
            for (let i = 1; i < filas.length; i++) {
                let celdas = filas[i].getElementsByTagName("td");
                let mostrarFila = false;

                // Verificar cada celda en la fila
                for (let j = 0; j < celdas.length; j++) {
                    if (celdas[j]) {
                        let textoCelda = celdas[j].textContent || celdas[j].innerText;
                        if (textoCelda.toLowerCase().indexOf(filtro) > -1) {
                            mostrarFila = true;
                            break;
                        }
                    }
                }

                // Mostrar u ocultar la fila según el resultado
                filas[i].style.display = mostrarFila ? "" : "none";
            }
        }

        // Función para ordenar columnas
        function ordenarTabla(indiceColumna) {
            let tabla = document.getElementById("tablaReservas");
            let filas = Array.from(tabla.rows).slice(1);

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

            filas.forEach(fila => tabla.appendChild(fila));
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
            <!-- Barra de búsqueda -->
            <input type="text" id="buscar" placeholder="Buscar en la tabla..." onkeyup="filtrarTabla()">
        </h1>



        <?php
        require '../autenticacion/conexion.php';

        $emailUsuario = $_SESSION['email'];
        $sql = "SELECT r.id_reserva, l.titulo, r.fecha_reserva, r.fecha_expiracion, r.estado, r.email_usuario 
                FROM reservas r
                JOIN libros l ON r.isbn = l.isbn
                WHERE r.email_usuario = '$emailUsuario'
                ORDER BY r.fecha_reserva DESC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' id='tablaReservas'>";
            echo "<tr>
                <th onclick='ordenarTabla(0)'>Título del Libro</th>
                <th onclick='ordenarTabla(1)'>Usuario</th>
                <th onclick='ordenarTabla(2)'>Fecha de Reserva</th>
                <th onclick='ordenarTabla(3)'>Fecha de Expiración</th>
                <th onclick='ordenarTabla(4)'>Estado</th>
                <th>Opciones</th>
            </tr>";
            while ($row = $result->fetch_assoc()) {
                $tituloLibro = htmlspecialchars($row["titulo"]);
                $fechaReserva = htmlspecialchars($row["fecha_reserva"]);
                $fechaExpiracion = htmlspecialchars($row["fecha_expiracion"]);
                $estado = htmlspecialchars($row["estado"]);
                $emailUsuarioReserva = htmlspecialchars($row["email_usuario"]);
                
                echo "<tr>";
                echo "<td>$tituloLibro</td>";
                echo "<td>$emailUsuarioReserva</td>";
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
