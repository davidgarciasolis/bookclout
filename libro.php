<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Libro</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
    .book-details {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .book-details img {
        flex-shrink: 0;
        width: 200px; /* Fixed width for all book cover images */
        height: 300px; /* Fixed height for all book cover images */
        object-fit: cover; /* Ensures the image fits within the dimensions without distortion */
        border-radius: 5px;
        margin: 10px 0;
    }

    .book-details-content {
        flex-grow: 1;
    }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main>
        <?php
        // Include the database connection
        include 'autenticacion/conexion.php';

        // Check if ISBN is provided
        if (isset($_GET['isbn'])) {
            $isbn = $conn->real_escape_string($_GET['isbn']);

            // Updated query to include editorial and fecha_publicacion
            $query = "SELECT titulo, autor, genero, descripcion, portada, editorial, fecha_publicacion, unidades FROM libros WHERE isbn = '$isbn'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $libro = $result->fetch_assoc();

                // Query to calculate available units
                $query_unidades = "SELECT (libros.unidades - 
                    (SELECT COUNT(*) FROM reservas WHERE isbn = '$isbn' AND estado = 'activa') - 
                    (SELECT COUNT(*) FROM prestamos WHERE isbn = '$isbn' AND fecha_devolucion IS NULL)) AS unidades_disponibles 
                    FROM libros WHERE isbn = '$isbn'";
                $result_unidades = $conn->query($query_unidades);
                $unidades_disponibles = $result_unidades->fetch_assoc()['unidades_disponibles'];

                echo "<section class='book-details'>";
                echo "<img src='" . htmlspecialchars($libro['portada']) . "' alt='Portada de " . htmlspecialchars($libro['titulo']) . "'>";
                echo "<div class='book-details-content'>";
                echo "<h1>" . htmlspecialchars($libro['titulo']) . "</h1>";
                echo "<p><strong>Autor:</strong> " . htmlspecialchars($libro['autor']) . "</p>";
                echo "<p><strong>Género:</strong> " . htmlspecialchars($libro['genero']) . "</p>";
                echo "<p><strong>Editorial:</strong> " . htmlspecialchars($libro['editorial']) . "</p>";
                echo "<p><strong>Fecha de publicación:</strong> " . htmlspecialchars($libro['fecha_publicacion']) . "</p>";
                echo "<p><strong>Descripción:</strong> " . htmlspecialchars($libro['descripcion']) . "</p>";

                if ($unidades_disponibles > 0) {
                    echo "<form action='php/procesar_reserva.php' method='POST' class='reservation-form'>";
                    echo "<p><strong>Unidades disponibles:</strong> " . htmlspecialchars($unidades_disponibles) . "</p>";
                    echo "<input type='hidden' name='isbn' value='" . htmlspecialchars($isbn) . "'>";
                    echo "<button type='submit' class='btn btn-available'>Reservar</button>";
                    echo "</form>";
                } else {
                    echo "<form class='reservation-form'>";
                    echo "<p><strong>Unidades disponibles:</strong> " . htmlspecialchars($unidades_disponibles) . "</p>";
                    echo "<button type='button' class='btn btn-unavailable' disabled>No Disponible</button>";
                    echo "</form>";
                }

                echo "</div>"; // Close book-details-content
                echo "</section>";
            } else {
                echo "<p>No se encontró el libro con el ISBN proporcionado.</p>";
            }
        } else {
            echo "<p>No se proporcionó un ISBN válido.</p>";
        }

        $conn->close();
        ?>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
