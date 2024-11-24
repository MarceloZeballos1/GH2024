<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';

// Información del usuario
$user_id = $_SESSION['user_id'];
$user_info = $conn->prepare("SELECT * FROM users WHERE id = :id");
$user_info->execute(['id' => $user_id]);
$user = $user_info->fetch();

// Órdenes del usuario
$orders = $conn->prepare("SELECT wo.*, eq.name as equipment_name FROM work_orders wo 
    JOIN equipments eq ON wo.equipment_id = eq.id WHERE wo.user_id = :user_id AND wo.status = 'pending'");
$orders->execute(['user_id' => $user_id]);

// Equipos disponibles
$equipments = $conn->query("SELECT id, name FROM equipments")->fetchAll();

// Crear nueva orden de trabajo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    $equipment_id = $_POST['equipment_id'];
    $description = htmlspecialchars($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO work_orders (user_id, equipment_id, description, status) VALUES (:user_id, :equipment_id, :description, 'pending')");
    $stmt->execute(['user_id' => $user_id, 'equipment_id' => $equipment_id, 'description' => $description]);
    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <SCRIPT LANGUAGE="JavaScript">
    history.forward()
    </SCRIPT>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Panel de Usuario</h1>
        <h3>Bienvenido, <?= htmlspecialchars($user['username']); ?></h3>
        <div class="card mt-4">
            <div class="card-header">Órdenes Pendientes</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']); ?></td>
                                <td><?= htmlspecialchars($order['equipment_name']); ?></td>
                                <td><?= htmlspecialchars($order['description']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($order['status'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Crear Nueva Orden</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="equipment_id">Equipo</label>
                        <select name="equipment_id" id="equipment_id" class="form-select">
                            <?php foreach ($equipments as $equipment): ?>
                                <option value="<?= $equipment['id']; ?>"><?= htmlspecialchars($equipment['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description">Descripción</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <button type="submit" name="create_order" class="btn btn-primary">Crear Orden</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
