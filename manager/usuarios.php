<?php 
require '../autenticacion/check_sesion.php'; 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../css/styles.css">
    
    <script>
    // Variables para rastrear el estado de ordenación
    let columnaOrdenada = -1; // Ninguna columna está ordenada inicialmente
    let ordenAscendente = true; // Orden por defecto: ascendente

    function ordenarTabla(indiceColumna) {
        let tabla = document.getElementById("tablaUsuarios");
        let filas = Array.from(tabla.rows).slice(1); // Ignora la fila de encabezado

        // Si la misma columna se vuelve a ordenar, invertimos el orden
        if (columnaOrdenada === indiceColumna) {
            ordenAscendente = !ordenAscendente;
        } else {
            columnaOrdenada = indiceColumna; // Cambiamos a la nueva columna
            ordenAscendente = true; // Por defecto en ascendente
        }

        filas.sort((a, b) => {
            let celdaA = a.cells[indiceColumna].textContent.trim().toLowerCase();
            let celdaB = b.cells[indiceColumna].textContent.trim().toLowerCase();
            if (celdaA < celdaB) {
                return ordenAscendente ? -1 : 1;
            }
            if (celdaA > celdaB) {
                return ordenAscendente ? 1 : -1;
            }
            return 0;
        });

        filas.forEach(fila => tabla.appendChild(fila)); // Rearranga las filas
    }
    </script>

</head>
<body>
    <?php include 'includes/header.php';?>

    <main>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <h1>
            Usuarios
             <!-- Campo de búsqueda -->
        <input type="text" id="buscar" placeholder="Buscar en la tabla..." onkeyup="filtrarTabla()">
            <a href="alta_usuario.php"><button>Agregar Usuario</button></a>
            
        </h1>


        <?php
        require '../autenticacion/conexion.php';

        $sql = "SELECT nombre, email, admin, fecha_alta FROM usuarios"; 
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1' id='tablaUsuarios'>";
            echo "<tr>
                <th onclick='ordenarTabla(0)'>Nombre</th>
                <th onclick='ordenarTabla(1)'>Email</th>
                <th onclick='ordenarTabla(2)'>Admin</th>
                <th onclick='ordenarTabla(3)'>Fecha de Alta</th>
                <th>Opciones</th>
            </tr>";
            while($row = $result->fetch_assoc()) {
                $nombreUsuario = htmlspecialchars($row["nombre"]);
                $emailUsuario = htmlspecialchars($row["email"]);
                
                echo "<tr>";
                echo "<td>$nombreUsuario</td>";
                echo "<td>$emailUsuario</td>";
                echo "<td>" . ($row["admin"] ? 'Sí' : 'No') . "</td>";
                echo "<td>" . htmlspecialchars($row["fecha_alta"]) . "</td>";
                echo "<td>
                    <form action='modificar_usuario.php' method='POST'>
                        <input type='hidden' name='email' value='$emailUsuario'>
                        <button type='submit'>Modificar</button>
                    </form>
                    <form action='php/procesar_eliminar_usuario.php' method='POST' onsubmit='event.preventDefault(); confirmarEliminacion(\"$nombreUsuario\", this);'>
                        <input type='hidden' name='email' value='$emailUsuario'>
                        <button type='submit'>Eliminar</button>
                    </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay usuarios registrados.</p>";
        }

        $conn->close();
        ?>
    </main>

    <?php include 'includes/footer.php';?>
</body>
</html>


