<?php
// Incluir archivo para verificar la sesión activa
require '../../autenticacion/check_sesion.php';

// Incluir archivo para establecer la conexión con la base de datos
require '../../autenticacion/conexion.php';

// Verificar que la solicitud se realiza mediante el método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar que se envió el campo 'isbn' y que no esté vacío
    if (isset($_POST['isbn']) && !empty($_POST['isbn'])) {
        // Escapar el valor del ISBN para evitar inyección SQL
        $isbn = $conn->real_escape_string($_POST['isbn']);

        // Preparar consulta SQL para eliminar un libro con el ISBN especificado
        $sql = "DELETE FROM libros WHERE isbn = '$isbn'";
        
        // Ejecutar la consulta y verificar si se realizó correctamente
        if ($conn->query($sql) === TRUE) {
            // Guardar mensaje de éxito en la sesión
            $_SESSION['mensaje'] = "Libro eliminado exitosamente.";
        } else {
            // Guardar mensaje de error en la sesión si la eliminación falla
            $_SESSION['mensaje'] = "Error al eliminar el libro: " . $conn->error;
        }
    } else {
        // Guardar mensaje de error en la sesión si el ISBN no se proporciona
        $_SESSION['mensaje'] = "El ISBN es obligatorio.";
    }
} else {
    // Guardar mensaje de error en la sesión si el método de solicitud no es POST
    $_SESSION['mensaje'] = "Método de solicitud no permitido.";
}

// Cerrar la conexión con la base de datos
$conn->close();

// Redirigir de vuelta a la página de gestión de libros
header("Location: ../libros.php");
exit(); // Terminar el script después de redirigir
?>
