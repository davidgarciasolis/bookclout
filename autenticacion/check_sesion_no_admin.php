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
    } else {
        echo "<p>Error al verificar los permisos del usuario.</p>";
        exit();
    }
}
?>
