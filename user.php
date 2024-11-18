<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php'; 


// Obtener información del usuario logueado
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user_info = $stmt->fetch();

// Obtener órdenes de trabajo activas para el usuario
$orders = $conn->prepare("SELECT * FROM work_orders WHERE user_id = :user_id AND status = 'pending'");
$orders->execute(['user_id' => $user_id]);
$orders = $orders->fetchAll();

// Obtener equipos para el usuario
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Panel de Usuario</h1>
        <p>Bienvenido, <strong><?= $user_info['username']; ?></strong>!</p>
        <a href="logout.php" class="btn btn-secondary mb-4">Cerrar Sesión</a>

        <!-- Información de usuario -->
        <div class="card mb-4">
            <div class="card-header">Información de Usuario</div>
            <div class="card-body">
                <p><strong>Usuario:</strong> <?= $user_info['username']; ?></p>
                <p><strong>Rol:</strong> <?= $user_info['role']; ?></p>
            </div>
        </div>

        <!-- Ordenes de Trabajo -->
        <div class="card mb-4">
            <div class="card-header">Órdenes de Trabajo Pendientes</div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <p>No tienes órdenes de trabajo pendientes.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Equipo</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order['id']; ?></td>
                                    <td><?= $order['equipment_id']; ?></td>
                                    <td><?= $order['description']; ?></td>
                                    <td><?= $order['status']; ?></td>
                                    <td>
                                        <a href="work_orders.php?id=<?= $order['id']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Crear nueva Orden de Trabajo -->
        <div class="card">
            <div class="card-header">Crear Nueva Orden de Trabajo</div>
            <div class="card-body">
                <form method="POST" action="create_order.php">
                    <div class="mb-3">
                        <label for="equipment" class="form-label">Equipo</label>
                        <select class="form-select" id="equipment" name="equipment_id" required>
                            <?php foreach ($equipments as $equipment): ?>
                                <option value="<?= $equipment['id']; ?>"><?= $equipment['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción del Trabajo</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="create_order" class="btn btn-primary">Crear Orden</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
