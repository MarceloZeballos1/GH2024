<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';
require 'includes/header.php';

// Verifica el rol del usuario para mostrar contenido adecuado
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_id = $_SESSION['user_id'];

// Manejo de creación de órdenes de trabajo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    $equipment_id = $_POST['equipment_id'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO work_orders (user_id, equipment_id, description, status) VALUES (:user_id, :equipment_id, :description, 'pending')");
    $stmt->execute(['user_id' => $user_id, 'equipment_id' => $equipment_id, 'description' => $description]);

    $success_message = "Orden de trabajo creada exitosamente.";
}

// Obtener equipos disponibles para selección
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();

// Obtener órdenes de trabajo según el rol
if ($is_admin) {
    $orders = $conn->query("SELECT wo.*, u.username, e.name as equipment_name FROM work_orders wo
                            JOIN users u ON wo.user_id = u.id
                            JOIN equipments e ON wo.equipment_id = e.id")->fetchAll();
} else {
    $stmt = $conn->prepare("SELECT wo.*, e.name as equipment_name FROM work_orders wo
                            JOIN equipments e ON wo.equipment_id = e.id
                            WHERE wo.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $orders = $stmt->fetchAll();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Gestión de Órdenes de Trabajo</h1>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <!-- Lista de Órdenes de Trabajo -->
        <div class="card mb-4">
            <div class="card-header">Órdenes de Trabajo</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <?php if ($is_admin): ?>
                                <th>Usuario</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['id']; ?></td>
                                <td><?= $order['equipment_name']; ?></td>
                                <td><?= $order['description']; ?></td>
                                <td><?= $order['status']; ?></td>
                                <?php if ($is_admin): ?>
                                    <td><?= $order['username']; ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Crear nueva Orden de Trabajo (visible solo para usuarios normales) -->
        <?php if (!$is_admin): ?>
            <div class="card">
                <div class="card-header">Crear Nueva Orden de Trabajo</div>
                <div class="card-body">
                    <form method="POST">
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
        <?php endif; ?>
    </div>
</body>
</html>
