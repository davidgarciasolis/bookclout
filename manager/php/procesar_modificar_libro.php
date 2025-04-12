<?php
// Incluye el archivo que verifica la sesión del usuario
require '../../autenticacion/check_sesion.php';

// Incluye la conexión a la base de datos
require '../../autenticacion/conexion.php';

// Verifica si se envió el formulario con los datos del libro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escapa y almacena los datos enviados para prevenir XSS y SQL Injection
    $isbnActual = htmlspecialchars($_POST['isbn_actual']);
    $isbnNuevo = htmlspecialchars($_POST['isbn']);
    $tituloNuevo = htmlspecialchars($_POST['titulo']);
    $autorNuevo = htmlspecialchars($_POST['autor']);
    $generoNuevo = htmlspecialchars($_POST['genero']); // Capturar el nuevo campo 'género'
    $editorialNueva = htmlspecialchars($_POST['editorial']);
    $fechaNueva = htmlspecialchars($_POST['fecha_publicacion']);
    $portadaNueva = $_POST['portada'] ?? null;
    $descripcionNueva = htmlspecialchars($_POST['descripcion']);
    $unidadesNuevas = htmlspecialchars($_POST['unidades']); // Nuevo campo


    // Consulta SQL para actualizar el libro, incluyendo el género
    $sql = "UPDATE libros SET isbn = ?, titulo = ?, autor = ?, editorial = ?, fecha_publicacion = ?, portada = ?, descripcion = ?, unidades = ?, genero = ? WHERE isbn = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincula los parámetros y ejecuta la consulta
        $stmt->bind_param('sssssssssi', $isbnNuevo, $tituloNuevo, $autorNuevo, $editorialNueva, $fechaNueva, $portadaNueva, $descripcionNueva, $unidadesNuevas, $generoNuevo, $isbnActual);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Libro actualizado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el libro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta: " . $conn->error;
    }

    // Redirige de vuelta a la lista de libros
    header('Location: ../libros.php');
    exit;
} else {
    $_SESSION['mensaje'] = "Acción no permitida.";
    header('Location: ../libros.php');
    exit;
}
