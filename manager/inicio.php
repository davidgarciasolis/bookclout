<!-- Comprueba que la sesión está activa -->
<?php include '../autenticacion/check_sesion.php'; ?>
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
    <?php include 'includes/header.php';?>
    <main>
        <h1>Bienvenido <?php echo $_SESSION["usuario"]; ?></h1>
    </main>
    <!-- footer -->
    <?php include 'includes/footer.php';?>
</body>
</html>
