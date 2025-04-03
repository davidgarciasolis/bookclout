<?php include '../php/check_sesion.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- header -->
    <?php include 'header.php';?>

    <main>
        <h1>Usuarios</h1>
        <button>Agregar Usuario</button>
        
        <?php
        require '../php/conexion.php';

        // Consulta SQL
        $sql = "SELECT nombre, email, admin FROM usuarios"; // Ajusta 'usuarios' según el nombre real de tu tabla
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Nombre</th><th>Email</th><th>Admin</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . ($row["admin"] ? 'Sí' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay usuarios registrados.</p>";
        }

        // Cerrar conexión
        $conn->close();
        ?>
    </main>

    <!-- footer -->
    <?php include '../footer.php';?>
</body>
</html>

