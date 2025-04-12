<?php
session_start(); // Inicia la sesión para manejar datos entre páginas
require '../../autenticacion/conexion.php'; // Incluye el archivo para la conexión a la base de datos

// Verifica si la solicitud se realizó mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"]; // Obtiene el email del usuario enviado desde el formulario
    $contraseña = $_POST["contraseña"]; // Obtiene la contraseña enviada desde el formulario

    // Prepara una consulta SQL para buscar la contraseña del usuario en la base de datos
    $stmt = $conn->prepare("SELECT contraseña FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email); // Vincula el parámetro del nombre de usuario a la consulta
    $stmt->execute(); // Ejecuta la consulta
    $stmt->store_result(); // Almacena el resultado de la consulta

    // Verifica si se encontró al menos un usuario con el nombre proporcionado
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password); // Vincula el resultado de la consulta a la variable $hashed_password
        $stmt->fetch(); // Obtiene el valor de la contraseña encriptada

        // Verifica si la contraseña proporcionada coincide con la contraseña encriptada almacenada
        if (password_verify($contraseña, $hashed_password)) {
            $_SESSION["email"] = $email; // Guarda el nombre de usuario en la sesión
            header("Location: ../inicio.php"); // Redirige al usuario a la página principal
            exit(); // Finaliza la ejecución del script
        } else {
            $_SESSION["error"] = "El usuario o la contraseña es incorecta"; // Guarda un mensaje de error en la sesión
        }
    } else {
        $_SESSION["error"] = "El usuario o la contraseña es incorecta"; // Guarda un mensaje de error si el usuario no existe
    }
    $stmt->close(); // Cierra la consulta preparada
    $conn->close(); // Cierra la conexión a la base de datos
}

// Redirige al usuario de vuelta a la página de inicio de sesión
header("Location: ../login.php");
exit(); // Finaliza la ejecución del script
?>
