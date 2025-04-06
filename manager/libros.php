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
        function confirmarEliminacion(tituloLibro, form) {
            if (confirm(`¿Estás seguro de que quieres eliminar el libro "${tituloLibro}"?`)) {
                form.submit();
            }
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
            <a href="alta_libro.php"><button>Agregar Libro</button></a>
        </h1>

        <?php
        require '../autenticacion/conexion.php';

        // Incluye 'unidades' en la consulta SQL
        $sql = "SELECT isbn, titulo, autor, editorial, fecha_publicacion, portada, descripcion, unidades FROM libros"; 
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ISBN</th><th>Título</th><th>Autor</th><th>Editorial</th><th>Publicación</th><th>Portada</th><th>Unidades</th><th>Opciones</th></tr>";
            while($row = $result->fetch_assoc()) {
                $isbnLibro = htmlspecialchars($row["isbn"]);
                $tituloLibro = htmlspecialchars($row["titulo"]);
                $autorLibro = htmlspecialchars($row["autor"]);
                $editorialLibro = htmlspecialchars($row["editorial"]);
                $fechaLibro = htmlspecialchars($row["fecha_publicacion"]);
                $portadaLibro = htmlspecialchars($row["portada"]);
                $unidadesLibro = htmlspecialchars($row["unidades"]);

                echo "<tr>";
                echo "<td>$isbnLibro</td>";
                echo "<td>$tituloLibro</td>";
                echo "<td>$autorLibro</td>";
                echo "<td>" . ($editorialLibro ? $editorialLibro : 'No especificado') . "</td>";
                echo "<td>" . ($fechaLibro ? $fechaLibro : 'No especificada') . "</td>";
                echo "<td>";
                if ($portadaLibro) {
                    echo "<img src='$portadaLibro' alt='Portada' style='width:50px;height:auto;'>";
                } else {
                    echo "Sin portada";
                }
                echo "</td>";
                // Mostrar el número de unidades
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

