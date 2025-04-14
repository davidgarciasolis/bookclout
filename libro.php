<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- header -->
    <?php include 'includes/header.php';?>
    <main>
<?php
// Include the database connection
include 'autenticacion/conexion.php';

// Check if the 'isbn' parameter is provided
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Query to fetch book details by ISBN
    $query = "SELECT titulo, autor, genero, descripcion, portada FROM libros WHERE isbn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($book['titulo']); ?></title>
            <link rel="stylesheet" href="css/styles.css">
            <style>
                .book-container {
                    display: flex;
                    align-items: flex-start;
                    gap: 2rem;
                }
                .book-container img {
                    max-width: 300px;
                    max-height: 400px;
                }
                .book-details {
                    flex: 1;
                }
            </style>
        </head>
        <body>
            <div class="book-container">
                <img src="<?php echo htmlspecialchars($book['portada']); ?>" alt="Portada del libro">
                <div class="book-details">
                    <h1><?php echo htmlspecialchars($book['titulo']); ?></h1>
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($book['autor']); ?></p>
                    <p><strong>Género:</strong> <?php echo htmlspecialchars($book['genero']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($book['descripcion'])); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Libro no encontrado.</p>";
    }

    $stmt->close();
} else {
    echo "<p>ISBN no proporcionado.</p>";
}

$conn->close();
?>
    </main>
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
