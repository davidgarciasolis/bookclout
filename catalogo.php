<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    


<?php
include 'autenticacion/conexion.php';
?>




<?php
include 'includes/header.php';
// Pagination logic
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and filter logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$selected_genres = isset($_GET['genres']) ? $_GET['genres'] : [];
$genre_filter = '';
if (!empty($selected_genres)) {
    $genre_filter = "AND genero IN ('" . implode("','", array_map([$conn, 'real_escape_string'], $selected_genres)) . "')";
}

// Query to fetch books
$query = "SELECT * FROM libros WHERE titulo LIKE '%$search%' $genre_filter ORDER BY fecha_publicacion DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Query to fetch genres
$genres_query = "SELECT DISTINCT genero FROM libros ORDER BY genero";
$genres_result = $conn->query($genres_query);

// Query to count total books for pagination
$count_query = "SELECT COUNT(*) as total FROM libros WHERE titulo LIKE '%$search%' $genre_filter";
$count_result = $conn->query($count_query);
$total_books = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_books / $limit);
?>

<main>
    <div style="display: flex;">
        <!-- Sidebar for genres -->
        <aside style="width: 20%; padding: 10px;">
            <h3>Géneros</h3>
            <form method="GET" action="catalogo.php">
                <?php while ($genre = $genres_result->fetch_assoc()): ?>
                    <div>
                        <input type="checkbox" name="genres[]" value="<?php echo htmlspecialchars($genre['genero']); ?>" <?php echo in_array($genre['genero'], $selected_genres) ? 'checked' : ''; ?>>
                        <label><?php echo htmlspecialchars($genre['genero']); ?></label>
                    </div>
                <?php endwhile; ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </aside>

        <!-- Main content -->
        <section style="width: 80%; padding: 10px;">
            <h1>Catálogo de Libros</h1>

            <!-- Search bar -->
            <form method="GET" action="catalogo.php">
                <input type="text" name="search" placeholder="Buscar libros..." value="<?php echo htmlspecialchars($search); ?>">
                <?php foreach ($selected_genres as $genre): ?>
                    <input type="hidden" name="genres[]" value="<?php echo htmlspecialchars($genre); ?>">
                <?php endforeach; ?>
                <button type="submit">Buscar</button>
            </form>

            <!-- Book list -->
            <div class="book-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($book = $result->fetch_assoc()): ?>
                        <div class="book-item">
                            <img src="<?php echo htmlspecialchars($book['portada']); ?>" alt="Portada de <?php echo htmlspecialchars($book['titulo']); ?>">
                            <div class="book-item-content">
                                <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>
                                <p><?php echo htmlspecialchars($book['descripcion']); ?></p>
                                <a href="libro.php?isbn=<?php echo urlencode($book['isbn']); ?>">
                                    <button type="button" class="btn btn-available">Reservar</button>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="book-item" style="flex-grow: 1; justify-content: center; text-align: center;">
                        <div class="book-item-content">
                            <h3>No se han encontrado libros,
                            Intenta realizar otra búsqueda revisanodo o ajustar los filtros para encontrar una seleccion de libros.</h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="catalogo.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&<?php echo http_build_query(['genres' => $selected_genres]); ?>" style="margin: 0 5px; text-decoration: none; <?php echo $i === $page ? 'font-weight: bold;' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </section>
    </div>
</main>

<script>
    document.querySelectorAll('input[name="genres[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const form = checkbox.closest('form');
            form.submit();
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>