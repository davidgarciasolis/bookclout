<!-- Comprueba que la sesión está activa -->
<?php include '../php/check_sesion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- header -->
    <?php include 'header.php';?>
    <main>
        <h1>Bienvenido <?php echo $_SESSION["usuario"]; ?></h1>
    </main>
    <!-- footer -->
    <?php include '../footer.php';?>
</body>
</html>
