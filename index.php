<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Libros por Género</h1>
        <?php
        // Include the database connection
        include 'autenticacion/conexion.php';

        // Query to fetch books grouped by genre
        $query = "SELECT genero, titulo, portada, isbn FROM libros ORDER BY genero, titulo";
        $result = $conn->query($query);

        $categorias = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[$row['genero']][] = $row;
            }
        }

        foreach ($categorias as $genero => $libros) {
            echo "<section class='carousel'>";
            echo "<h2>" . htmlspecialchars($genero) . "</h2>";
            echo "<div class='carousel-container'>";

            foreach ($libros as $libro) {
                echo "<div class='carousel-item'>";
                echo "<a href='libro.php?isbn=" . urlencode($libro['isbn']) . "'>";
                echo "<img src='" . htmlspecialchars($libro['portada']) . "' alt='" . htmlspecialchars($libro['titulo']) . "'>";
                echo "</a>";
                echo "<p>" . htmlspecialchars($libro['titulo']) . "</p>";
                echo "</div>";
            }

            echo "</div>";
            echo "</section>";
        }

        $conn->close();
        ?>
    </main>

    <style>
    .carousel {
        margin: 20px 0;
        width: 100%;
        overflow: hidden;
    }

    .carousel-container {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        scroll-snap-type: x mandatory;
        padding: 10px;
        box-sizing: border-box;
    }

    .carousel-item {
        flex: 0 0 auto;
        width: calc(100% / 3); /* Ajustado para mostrar 3 elementos a la vez */
        max-width: 300px;
        text-align: center;
        scroll-snap-align: start;
        background-color: #f9f9f9; /* Fondo uniforme */
        border: 1px solid #ddd; /* Borde para separar los elementos */
        border-radius: 8px; /* Bordes redondeados */
        padding: 10px; /* Espaciado interno */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para un efecto elegante */
    }

    .carousel-item img {
        width: 100%; /* Ajustado para ocupar todo el ancho del contenedor */
        height: 200px; /* Altura fija para uniformidad */
        object-fit: cover; /* Recortar la imagen para que se ajuste al contenedor */
        border-radius: 5px; /* Bordes redondeados para las imágenes */
        margin-bottom: 10px; /* Espaciado entre la imagen y el título */
    }

    .carousel-item p {
        font-size: 1rem; /* Tamaño de fuente uniforme */
        color: #333; /* Color de texto consistente */
        margin: 0; /* Eliminar márgenes adicionales */
    }

    @media (max-width: 768px) {
        .carousel-item {
            width: calc(100% / 2); /* Show 2 items on smaller screens */
        }
    }

    @media (max-width: 480px) {
        .carousel-item {
            width: 100%; /* Show 1 item on very small screens */
        }
    }
    </style>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
