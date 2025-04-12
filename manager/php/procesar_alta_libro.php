<?php
require '../../autenticacion/check_sesion.php';
require '../../autenticacion/conexion.php';

try {
    // Obtener datos del formulario, incluyendo el nuevo campo de género y unidades
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'] ?? null; // Capturar el género, con valor predeterminado de null
    $editorial = $_POST['editorial'] ?? null;
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? null;
    $portada = $_POST['portada'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $unidades = $_POST['unidades'] ?? 0; // Capturar las unidades, con valor predeterminado de 0

    // Preparar la consulta para insertar datos en la tabla 'libros'
    $sql = "INSERT INTO libros (isbn, titulo, autor, genero, editorial, fecha_publicacion, portada, descripcion, unidades) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind de parámetros
        $stmt->bind_param("ssssssssi", $isbn, $titulo, $autor, $genero, $editorial, $fecha_publicacion, $portada, $descripcion, $unidades);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Libro registrado exitosamente.";
            $_SESSION['mensaje_tipo'] = "exito";
        } else {
            $_SESSION['mensaje'] = "El libro no se ha registrado. Por favor, intenta nuevamente.";
            $_SESSION['mensaje_tipo'] = "error";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['mensaje_tipo'] = "error";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: No se pudo registrar el libro. Detalles: " . $e->getMessage();
    $_SESSION['mensaje_tipo'] = "error";
}

// Cerrar la conexión
$conn->close();

// Redirigir a la página de alta de libros
header("Location: ../alta_libro.php");
exit;
?>
