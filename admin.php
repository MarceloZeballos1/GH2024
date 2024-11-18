<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php'; // Asegúrate de incluir la conexión a la base de datos

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Datos para estadísticas
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_equipments = $conn->query("SELECT COUNT(*) FROM equipments")->fetchColumn();
$pending_maintenances = $conn->query("SELECT COUNT(*) FROM maintenance_schedule WHERE status = 'pending'")->fetchColumn();

$users = $conn->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Panel de Administración</h1>
        <p>Bienvenido, <strong><?= $_SESSION['username']; ?></strong>!</p>
        <a href="logout.php" class="btn btn-secondary mb-4">Cerrar Sesión</a>

        <!-- Enlace al Dashboard -->
        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-primary">Ver Dashboard</a>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Usuarios Totales</div>
                    <div class="card-body"><?= $total_users; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Equipos Totales</div>
                    <div class="card-body"><?= $total_equipments; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Mantenimiento Pendiente</div>
                    <div class="card-body"><?= $pending_maintenances; ?></div>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="card mb-4">
            <div class="card-header">Gestión de Usuarios</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id']; ?></td>
                                <td><?= $user['username']; ?></td>
                                <td><?= $user['role']; ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <select class="form-select form-select-sm" name="role">
                                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>Usuario</option>
                                        </select>
                                        <button type="submit" name="update_role" class="btn btn-warning btn-sm">Actualizar Rol</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enlace para gestionar órdenes -->
        <div class="mb-4">
            <a href="work_orders.php" class="btn btn-info">Gestionar Órdenes de Trabajo</a>
        </div>

    </div>
</body>
</html>
