<?php
require '../autenticacion/check_sesion.php';
require '../autenticacion/conexion.php';

// Verifica si se recibió el ISBN del libro a editar
if (isset($_POST['isbn'])) {
    $isbnLibro = htmlspecialchars($_POST['isbn']);

    // Consulta SQL para obtener los datos del libro, incluyendo las unidades
    $sql = "SELECT isbn, titulo, autor, editorial, fecha_publicacion, portada, descripcion, unidades, genero FROM libros WHERE isbn = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $isbnLibro);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $libro = $result->fetch_assoc();
        } else {
            $_SESSION['mensaje'] = "Libro no encontrado.";
            header('Location: libros.php');
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
        header('Location: libros.php');
        exit;
    }
} else {
    $_SESSION['mensaje'] = "No se especificó un libro.";
    header('Location: libros.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Libro</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include 'includes/header.php';?>

    <main>
        <form action="php/procesar_modificar_libro.php" method="POST">
            <h1>Modificar Libro: <?php echo htmlspecialchars($libro['titulo']); ?></h1>
            <input type="hidden" name="isbn_actual" value="<?php echo htmlspecialchars($libro['isbn']); ?>">

            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn" value="<?php echo htmlspecialchars($libro['isbn']); ?>" required>

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>

            <label for="autor">Autor:</label>
            <input type="text" name="autor" id="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>

            <label for="genero">Género:</label>
            <input type="text" name="genero" id="genero" value="<?php echo htmlspecialchars($libro['genero'] ?? ''); ?>">

            <label for="editorial">Editorial:</label>
            <input type="text" name="editorial" id="editorial" value="<?php echo htmlspecialchars($libro['editorial']); ?>">

            <label for="fecha_publicacion">Fecha de Publicación:</label>
            <input type="date" name="fecha_publicacion" id="fecha_publicacion" value="<?php echo htmlspecialchars($libro['fecha_publicacion']); ?>">

            <label for="portada">URL de la Portada:</label>
            <input type="url" name="portada" id="portada" maxlength="2083" value="<?php echo htmlspecialchars($libro['portada']); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="4"><?php echo htmlspecialchars($libro['descripcion']); ?></textarea>
            <br><br>

            <label for="unidades">Unidades:</label>
            <input type="number" name="unidades" id="unidades" value="<?php echo htmlspecialchars($libro['unidades']); ?>" required>
            <br><br>

            <button type="submit">Actualizar Libro</button>
        </form>
    </main>

    <?php include 'includes/footer.php';?>
</body>
</html>

