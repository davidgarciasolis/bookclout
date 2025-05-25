<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .search-bar {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .search-bar input, .search-bar select, .search-bar button {
            padding: 10px;
            font-size: 16px;
            height: 40px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar select {
            min-width: 150px;
        }

        .search-bar button {
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: darkgreen;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
        }

        .book-item {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .book-item:hover {
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .book-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .book-item h3 {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Catálogo de Libros</h1>

        <div class="search-bar">
            <input type="text" id="search" placeholder="Buscar por título...">
            <select id="genre-filter">
                <option value="">Todos los géneros</option>
                <?php
                include 'autenticacion/conexion.php';
                $genresQuery = "SELECT DISTINCT genero FROM libros ORDER BY genero";
                $genresResult = $conn->query($genresQuery);

                if ($genresResult->num_rows > 0) {
                    while ($genre = $genresResult->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($genre['genero']) . "'>" . htmlspecialchars($genre['genero']) . "</option>";
                    }
                }
                ?>
            </select>
            <button onclick="filterBooks()">Buscar</button>
        </div>

        <div class="book-grid" id="book-grid">
            <?php
            $query = "SELECT genero, titulo, portada, isbn FROM libros ORDER BY titulo";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($book = $result->fetch_assoc()) {
                    echo "<a href='libro.php?isbn=" . urlencode($book['isbn']) . "' class='book-item' data-genre='" . htmlspecialchars($book['genero']) . "'>";
                    echo "<img src='" . htmlspecialchars($book['portada']) . "' alt='" . htmlspecialchars($book['titulo']) . "'>";
                    echo "<h3>" . htmlspecialchars($book['titulo']) . "</h3>";
                    echo "</a>";
                }
            } else {
                echo "<p>No se ha encontrado ningún libro.</p>";
            }

            $conn->close();
            ?>
        </div>
    </main>

    <script>
        function filterBooks() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const genreFilter = document.getElementById('genre-filter').value;
            const books = document.querySelectorAll('.book-item');
            let hasVisibleBooks = false;

            books.forEach(book => {
                const title = book.querySelector('h3').textContent.toLowerCase();
                const genre = book.getAttribute('data-genre');

                if ((title.includes(searchInput) || searchInput === '') && (genre === genreFilter || genreFilter === '')) {
                    book.style.display = '';
                    hasVisibleBooks = true;
                } else {
                    book.style.display = 'none';
                }
            });

            const noBooksMessage = document.getElementById('no-books-message');
            if (!hasVisibleBooks) {
                if (!noBooksMessage) {
                    const message = document.createElement('p');
                    message.id = 'no-books-message';
                    message.textContent = 'No se ha encontrado ningún libro.';
                    document.getElementById('book-grid').appendChild(message);
                }
            } else if (noBooksMessage) {
                noBooksMessage.remove();
            }
        }

        document.getElementById('genre-filter').addEventListener('change', filterBooks);
    </script>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
