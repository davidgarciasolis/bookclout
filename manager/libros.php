<?php 
// Incluye un archivo PHP que verifica la sesión del usuario
require '../autenticacion/check_sesion.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros</title>
    
    <!-- Vincula un archivo CSS externo para estilizar la página -->
    <link rel="stylesheet" href="../css/styles.css">
    
    <script>
        // Función para confirmar antes de eliminar un libro
        function confirmarEliminacion(tituloLibro, form) {
            if (confirm(`¿Estás seguro de que quieres eliminar el libro "${tituloLibro}"?`)) {
                form.submit();
            }
        }
    </script>
</head>
<body>
    <!-- Incluye el encabezado común de la aplicación -->
    <?php include 'includes/header.php';?>

    <main>
        <?php
        // Verifica si hay algún mensaje almacenado en la sesión
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <!-- Título principal de la página y botón para agregar un nuevo libro -->
        <h1>
            Libros
            <a href="alta_libro.php"><button>Agregar Libro</button></a>
        </h1>

        <?php
        // Conexión a la base de datos
        require '../autenticacion/conexion.php';

        // Consulta SQL para obtener información de los libros registrados
        $sql = "SELECT isbn, titulo, autor, editorial, fecha_publicacion, portada, descripcion FROM libros"; 
        $result = $conn->query($sql);

        // Si hay resultados, muestra los libros en una tabla; si no, muestra un mensaje
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ISBN</th><th>Título</th><th>Autor</th><th>Editorial</th><th>Publicación</th><th>Portada</th><th>Opciones</th></tr>";
            while($row = $result->fetch_assoc()) {
                // Escapa caracteres especiales para prevenir ataques XSS
                $isbnLibro = htmlspecialchars($row["isbn"]);
                $tituloLibro = htmlspecialchars($row["titulo"]);
                $autorLibro = htmlspecialchars($row["autor"]);
                $editorialLibro = htmlspecialchars($row["editorial"]);
                $fechaLibro = htmlspecialchars($row["fecha_publicacion"]);
                $portadaLibro = htmlspecialchars($row["portada"]);

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

        // Cierra la conexión a la base de datos
        $conn->close();
        ?>
    </main>

    <!-- Incluye el pie de página común de la aplicación -->
    <?php include 'includes/footer.php';?>
</body>
</html>
