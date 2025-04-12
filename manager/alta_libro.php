<?php 
// Comprobar que el usuario tiene una sesión activa
include '../autenticacion/check_sesion.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Libros</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Encabezado -->
    <?php include 'includes/header.php'; ?>

    <main>
        <?php
        // Mensajes de éxito o error
        if (isset($_SESSION['mensaje'])) {
            $mensaje = $_SESSION['mensaje'];
            $tipo = $_SESSION['mensaje_tipo'];
            
            if ($tipo == 'exito') {
                echo "<script>alert('" . $mensaje . "');</script>";
            } elseif ($tipo == 'error') {
                echo "<script>alert('" . $mensaje . "');</script>";
            }

            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
        }
        ?>

        <h1>
            Alta de Libros
            <button id="boton">Cargar datos de google book</button>
        </h1>

        <!-- Formulario para registrar libros -->
        <form action="php/procesar_alta_libro.php" method="POST">

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" maxlength="20" required>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" maxlength="255" required>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" maxlength="255" required>

            <label for="genero">Género:</label>
            <input type="text" id="genero" name="genero" maxlength="255">

            <label for="editorial">Editorial:</label>
            <input type="text" id="editorial" name="editorial" maxlength="255">

            <label for="fecha_publicacion">Fecha de Publicación:</label>
            <input type="date" id="fecha_publicacion" name="fecha_publicacion">

            <label for="portada">Enlace a la Portada:</label>
            <input type="url" id="portada" name="portada" maxlength="2083">

            <label for="descripcion">Descripcion:</label>
            <textarea name="descripcion" id="descripcion" rows="4">Descripcion</textarea>
            <br><br>

            <label for="unidades">Unidades:</label>
            <input type="number" id="unidades" name="unidades" min="0" value="0" required>
            <br><br>

            <button type="submit">Registrar Libro</button>
        </form>
    </main>
    
    <!-- Pie de página -->
    <?php include 'includes/footer.php'; ?>
</body>
<script src="js/api_google_books.js"></script>
</html>

