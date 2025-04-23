<?php
session_start();
?>
<header>
    <div>
        <!-- Logo -->
        <link rel="icon" type="image/png" href="img/logo.png">
        <h1><a href="index.php">Bookclout</a></h1>

        <!-- Opciones -->
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <?php if (isset($_SESSION['email'])): ?>
                <li><a href="autenticacion/logout_user.php">Cerrar sesión</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>