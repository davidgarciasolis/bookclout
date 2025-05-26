<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT admin FROM usuarios WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['admin'] == 1) {
            echo "<p>No tienes permiso para acceder a esta página como administrador.</p>";
            echo "<form action='autenticacion/logout_user.php' method='POST'>";
            echo "<button type='submit'>Volver</button>";
            echo "</form>";
            exit();
        }
    } else {
        echo "<p>Error al verificar los permisos del usuario.</p>";
        exit();
    }
}
