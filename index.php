<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
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
        $query = "SELECT genero, titulo, portada FROM libros ORDER BY genero, titulo";
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
                echo "<img src='" . htmlspecialchars($libro['portada']) . "' alt='" . htmlspecialchars($libro['titulo']) . "'>";
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
        width: calc(100% / 3); /* Adjust to show 3 items at a time */
        max-width: 300px;
        text-align: center;
        scroll-snap-align: start;
    }

    .carousel-item img {
        width: 50%; /* Adjusted to occupy half the width */
        height: auto;
        border-radius: 5px;
        margin: 0 auto; /* Center the image */
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