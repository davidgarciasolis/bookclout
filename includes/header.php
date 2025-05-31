<?php
session_start();
?>
<header class="main-header">
    <div class="header-container">
        <!-- Logo -->
<h1 class="logo">
    <img src="img/zorro_web.png" alt="Zorro" style="height: 40px; vertical-align: middle; margin-right: 8px;">
    <a href="index.php">Bookcloud</a>
</h1>
        <!-- Opciones -->
        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item"><a href="index.php">Inicio</a></li>
                <li class="nav-item"><a href="catalogo.php">Catalogo</a></li>
                <li class="nav-item"><a href="reservas_prestamos.php">Reservas y prestamos</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                    <li class="nav-item"><a href="perfil.php">Perfil</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
