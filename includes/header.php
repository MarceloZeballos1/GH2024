<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recuperar el rol del usuario
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <img src="./logo.png" alt="Logo" style="height: 90px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="inventory.php">Inventario</a></li>
                <li class="nav-item"><a class="nav-link" href="cronogramaM.php">Cronogramas</a></li>
                <li class="nav-item"><a class="nav-link" href="kpi.php">KPI</a></li>
                <li class="nav-item"><a class="nav-link" href="hojaDeVida.php">HDR</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="ficha_tecnica.php">Ficha Tecnica</a></li>

                <!-- Mostrar Gestión de Usuarios solo si el rol es admin -->
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="users.php">Gestión de Usuarios</a></li>
                <?php endif; ?>

                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
</nav>
