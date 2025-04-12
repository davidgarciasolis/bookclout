<?php 
require '../autenticacion/check_sesion.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros</title>
    <link rel="stylesheet" href="../css/styles.css">

    <script>
        // Variables globales para el estado de ordenación
        let columnaOrdenada = -1;
        let ordenAscendente = true;

        // Confirmación antes de eliminar
        function confirmarEliminacion(tituloLibro, form) {
            if (confirm(`¿Estás seguro de que quieres eliminar el libro "${tituloLibro}"?`)) {
                form.submit();
            }
        }

        // Filtrar libros
        function filtrarTabla() {
            let filtro = document.getElementById("buscar").value.toLowerCase();
            let filas = document.querySelectorAll("table tr:not(:first-child)");

            filas.forEach(fila => {
                let textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? "" : "none";
            });
        }

        // Ordenar columnas
        function ordenarTabla(indiceColumna) {
            let tabla = document.getElementById("tablaLibros");
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
    <?php include 'includes/header.php';?>

    <main>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <h1>
            Libros
            <!-- Barra de búsqueda -->
            <input type="text" id="buscar" placeholder="Buscar en la tabla..." onkeyup="filtrarTabla()">
            <a href="alta_libro.php"><button>Agregar Libro</button></a>
        </h1>


        <?php
        require '../autenticacion/conexion.php';

        $sql = "SELECT isbn, titulo, autor, editorial, fecha_publicacion, portada, descripcion, unidades, genero FROM libros"; 
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' id='tablaLibros'>";
            echo "<tr>
                <th onclick='ordenarTabla(0)'>ISBN</th>
                <th onclick='ordenarTabla(1)'>Título</th>
                <th onclick='ordenarTabla(2)'>Autor</th>
                <th onclick='ordenarTabla(7)'>Género</th>
                <th onclick='ordenarTabla(3)'>Editorial</th>
                <th onclick='ordenarTabla(4)'>Publicación</th>
                <th>Portada</th>
                <th onclick='ordenarTabla(6)'>Unidades</th>
                <th>Opciones</th>
            </tr>";
            while($row = $result->fetch_assoc()) {
                $isbnLibro = htmlspecialchars($row["isbn"]);
                $tituloLibro = htmlspecialchars($row["titulo"]);
                $autorLibro = htmlspecialchars($row["autor"]);
                $editorialLibro = htmlspecialchars($row["editorial"]);
                $fechaLibro = htmlspecialchars($row["fecha_publicacion"]);
                $portadaLibro = htmlspecialchars($row["portada"]);
                $unidadesLibro = htmlspecialchars($row["unidades"]);
                $generoLibro = htmlspecialchars($row["genero"]);

                echo "<tr>";
                echo "<td>$isbnLibro</td>";
                echo "<td>$tituloLibro</td>";
                echo "<td>$autorLibro</td>";
                echo "<td>" . ($generoLibro ? $generoLibro : 'No especificado') . "</td>";
                echo "<td>" . ($editorialLibro ? $editorialLibro : 'No especificado') . "</td>";
                echo "<td>" . ($fechaLibro ? $fechaLibro : 'No especificada') . "</td>";
                echo "<td>";
                if ($portadaLibro) {
                    echo "<img src='$portadaLibro' alt='Portada' style='width:50px;height:auto;'>";
                } else {
                    echo "Sin portada";
                }
                echo "</td>";
                echo "<td>$unidadesLibro</td>";
                echo "<td>
                    <form action='modificar_libro.php' method='POST'>
                        <input type='hidden' name='isbn' value='$isbnLibro'>
                        <button type='submit'>Modificar</button>
                    </form>
                    <form action='php/procesar_eliminar_libro.php' method='POST' onsubmit='event.preventDefault(); confirmarEliminacion(\"$tituloLibro\", this);'>
                        <input type='hidden' name='isbn' value='$isbnLibro'>
                        <button type='submit'>Eliminar</button>
                    </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay libros registrados.</p>";
        }

        $conn->close();
        ?>
    </main>

    <?php include 'includes/footer.php';?>
</body>
</html>
