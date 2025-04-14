<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/carousel.css">
</head>
<body>
    <!-- header -->
    <?php include 'includes/header.php';?>

    <?php
    // Function to generate a carousel for a given genre
    function generarCarrusel($genero) {
        include 'autenticacion/conexion.php';

        // Query to fetch books of the given genre
        $query = "SELECT portada, isbn FROM libros WHERE genero = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $genero);
        $stmt->execute();
        $result = $stmt->get_result();

        // Generate the carousel HTML
        $html = "<h1>" . htmlspecialchars($genero) . "</h1>";
        $html .= '<div style="display: flex; overflow-x: auto; gap: 1rem;">';
        while ($row = $result->fetch_assoc()) {
            $html .= '<div style="flex: 0 0 150px; height: 250px; text-align: center; box-sizing: border-box; overflow: hidden;">';
            $html .= '<a href="libro.php?isbn=' . htmlspecialchars($row['isbn']) . '">';
            $html .= '<img src="' . htmlspecialchars($row['portada']) . '" alt="Portada del libro" style="max-width: 100%; max-height: 80%; margin-bottom: 0.5rem;">';
            $html .= '</a>';
            $html .= '</div>';
        }
        $html .= '</div>';

        $stmt->close();
        return $html;
    }

    // Example usage
    $genero = 'Ficcion';
    ?>
    
    <main>
        <?php echo generarCarrusel($genero); ?>
    </main>
</body>
</html>