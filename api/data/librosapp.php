<?php
require_once "../../autenticacion/conexion.php";
require_once "../auth/validate.php"; 

header("Content-Type: application/json");

$sql = "SELECT isbn, titulo, autor, editorial, fecha_publicacion, portada, descripcion, unidades, genero FROM libros";
$result = $conn->query($sql);

$libros = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $libros[] = [
            "isbn" => $row["isbn"],
            "titulo" => $row["titulo"],
            "autor" => $row["autor"],
            "editorial" => $row["editorial"],
            "fecha_publicacion" => $row["fecha_publicacion"],
            "portada" => $row["portada"], 
            "descripcion" => $row["descripcion"],
            "unidades" => $row["unidades"],
            "genero" => $row["genero"]
        ];
    }
}

echo json_encode($libros, JSON_UNESCAPED_UNICODE);
$conn->close();
