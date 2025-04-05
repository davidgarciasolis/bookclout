<?php 
// Incluye un archivo para verificar que el usuario tiene una sesión activa
include '../autenticacion/check_sesion.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Especifica el conjunto de caracteres y escala la vista para dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Crear Préstamo</title>
    <!-- Enlace al archivo CSS para estilos -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Enlace para usar select2.js (biblioteca para listas desplegables con buscador) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <!-- header: Incluye el encabezado común desde un archivo externo -->
    <?php include 'includes/header.php';?>

    <main>
        <?php
        // Comprueba si existe un mensaje en la sesión para mostrar al usuario
        if (isset($_SESSION['mensaje'])) {
            $mensaje = $_SESSION['mensaje']; // Almacena el mensaje de la sesión
            $tipo = $_SESSION['mensaje_tipo']; // Almacena el tipo de mensaje (éxito o error)
            
            // Mostrar el mensaje según el tipo
            echo "<script>alert('" . htmlspecialchars($mensaje) . "');</script>";
            
            // Limpiar el mensaje de la sesión después de mostrarlo
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
        }

        // Conexión a la base de datos para obtener los datos de libros y usuarios
        require '../autenticacion/conexion.php';

        // Obtener los ISBN de libros disponibles
        $sqlLibros = "SELECT isbn, titulo FROM libros";
        $resultLibros = $conn->query($sqlLibros);

        // Obtener los emails de usuarios disponibles
        $sqlUsuarios = "SELECT email, nombre FROM usuarios";
        $resultUsuarios = $conn->query($sqlUsuarios);
        ?>

        <!-- Formulario para crear un nuevo préstamo -->
        <form action="php/procesar_alta_prestamo.php" method="POST">
            <!-- Título principal de la página -->
            <h1>Alta de Préstamo</h1>
            
            <!-- Campo para seleccionar el libro (usando ISBN) -->
            <label for="isbn">Libro (ISBN):</label>
            <select id="isbn" name="isbn" required class="select-buscador">
                <option value="" disabled selected>Seleccione un libro</option>
                <?php
                if ($resultLibros->num_rows > 0) {
                    while($row = $resultLibros->fetch_assoc()) {
                        $isbn = htmlspecialchars($row["isbn"]);
                        $titulo = htmlspecialchars($row["titulo"]);
                        echo "<option value='$isbn'>$isbn - $titulo</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            
            <!-- Campo para seleccionar el usuario (usando email) -->
            <label for="email_usuario">Usuario (Email):</label>
            <select id="email_usuario" name="email_usuario" required class="select-buscador">
                <option value="" disabled selected>Seleccione un usuario</option>
                <?php
                if ($resultUsuarios->num_rows > 0) {
                    while($row = $resultUsuarios->fetch_assoc()) {
                        $email = htmlspecialchars($row["email"]);
                        $nombre = htmlspecialchars($row["nombre"]);
                        echo "<option value='$email'>$nombre ($email)</option>";
                    }
                }
                ?>
            </select>
            <br><br>

            <!-- Botón para enviar el formulario -->
            <button type="submit">Crear Préstamo</button>
        </form>
        
        <?php 
        // Cerrar las conexiones después de usar los datos
        $conn->close();
        ?>
    </main>

    <!-- footer: Incluye el pie de página común desde un archivo externo -->
    <?php include 'includes/footer.php';?>

    <!-- Scripts para habilitar el buscador en los selects -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select-buscador').select2();
        });
    </script>
</body>
</html>
