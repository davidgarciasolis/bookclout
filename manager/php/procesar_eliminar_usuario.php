<?php
// Incluir archivo para verificar la sesión activa
require '../../autenticacion/check_sesion.php';

// Incluir archivo para establecer la conexión con la base de datos
require '../../autenticacion/conexion.php';

// Verificar que la solicitud se realiza mediante el método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar que se envió el campo 'email' y que no esté vacío
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        // Escapar el valor del email para evitar inyección SQL
        $email = $conn->real_escape_string($_POST['email']);

        // Preparar consulta SQL para eliminar un usuario con el email especificado
        $sql = "DELETE FROM usuarios WHERE email = '$email'";
        
        // Ejecutar la consulta y verificar si se realizó correctamente
        if ($conn->query($sql) === TRUE) {
            // Guardar mensaje de éxito en la sesión
            $_SESSION['mensaje'] = "Usuario eliminado exitosamente.";
        } else {
            // Guardar mensaje de error en la sesión si la eliminación falla
            $_SESSION['mensaje'] = "Error al eliminar usuario: " . $conn->error;
        }
    } else {
        // Guardar mensaje de error en la sesión si el email no se proporciona
        $_SESSION['mensaje'] = "El email es obligatorio.";
    }
} else {
    // Guardar mensaje de error en la sesión si el método de solicitud no es POST
    $_SESSION['mensaje'] = "Método de solicitud no permitido.";
}

// Cerrar la conexión con la base de datos
$conn->close();

// Redirigir de vuelta a la página de gestión de usuarios
header("Location: ../usuarios.php");
exit(); // Terminar el script después de redirigir
?>


