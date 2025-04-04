<?php 
// Incluye un archivo PHP que verifica si el usuario tiene una sesión activa.
// Esto es importante para evitar accesos no autorizados a la página.
require '../autenticacion/check_sesion.php'; 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    
    <!-- Vincula un archivo CSS externo para estilizar la página -->
    <link rel="stylesheet" href="../css/styles.css">
    
    <script>
        // Función para confirmar antes de eliminar un usuario.
        // Muestra un cuadro de diálogo con el nombre del usuario y, si se acepta, envía el formulario.
        function confirmarEliminacion(nombreUsuario, form) {
            if (confirm(`¿Estás seguro de que quieres eliminar al usuario ${nombreUsuario}?`)) {
                form.submit();
            }
        }
    </script>
</head>
<body>
    <!-- Incluye el encabezado común de la aplicación -->
    <?php include 'includes/header.php';?>

    <main>
        <?php
        // Verifica si hay algún mensaje almacenado en la sesión (por ejemplo, éxito o error).
        // Si existe, lo muestra mediante una alerta y lo elimina de la sesión para evitar redundancia.
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <!-- Título principal de la página y botón para agregar un nuevo usuario -->
        <h1>
            Usuarios
            <a href="alta_usuario.php"><button>Agregar Usuario</button></a>
        </h1>

        <?php
        // Conexión a la base de datos para ejecutar una consulta
        require '../autenticacion/conexion.php';

        // Consulta SQL para obtener información de los usuarios registrados
        $sql = "SELECT nombre, email, admin, fecha_alta FROM usuarios"; 
        $result = $conn->query($sql);

        // Si hay resultados, los muestra en una tabla; si no, muestra un mensaje informativo
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Nombre</th><th>Email</th><th>Admin</th><th>Fecha de Alta</th><th>Opciones</th></tr>";
            while($row = $result->fetch_assoc()) {
                // Escapa caracteres especiales para prevenir ataques XSS
                $nombreUsuario = htmlspecialchars($row["nombre"]);
                $emailUsuario = htmlspecialchars($row["email"]);
                
                echo "<tr>";
                echo "<td>$nombreUsuario</td>";
                echo "<td>$emailUsuario</td>";
                echo "<td>" . ($row["admin"] ? 'Sí' : 'No') . "</td>";
                echo "<td>" . htmlspecialchars($row["fecha_alta"]) . "</td>";
                echo "<td>
                     <form action='modificar_usuario.php' method='POST'>
                        <!-- Incluye el correo electrónico del usuario en un campo oculto -->
                        <input type='hidden' name='email' value='$emailUsuario'>
                        <button type='submit'>Modificar</button>
                    </form>
                    <form action='php/procesar_eliminar_usuario.php' method='POST' onsubmit='event.preventDefault(); confirmarEliminacion(\"$nombreUsuario\", this);'>
                        <!-- Incluye el correo electrónico del usuario en un campo oculto -->
                        <input type='hidden' name='email' value='$emailUsuario'>
                        <button type='submit'>Eliminar</button>
                    </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            // Mensaje cuando no hay usuarios registrados
            echo "<p>No hay usuarios registrados.</p>";
        }

        // Cierra la conexión a la base de datos
        $conn->close();
        ?>
    </main>

    <!-- Incluye el pie de página común de la aplicación -->
    <?php include 'includes/footer.php';?>
</body>
</html>

