<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';

// Datos de estadísticas
$total_users = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_equipments = $conn->query("SELECT COUNT(*) FROM equipos_medicos")->fetchColumn();
$pending_maintenances = $conn->query("SELECT COUNT(*) FROM mantenimiento_programado WHERE estado = 'pending'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Admin Panel</h1>
        <div class="row">
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
                    <div class="card-header">Mantenimientos Pendientes</div>
                    <div class="card-body"><?= $pending_maintenances; ?></div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <a href="work_orders.php" class="btn btn-primary">Órdenes de Trabajo</a>
            <a href="equipments.php" class="btn btn-secondary">Equipos Médicos</a>
        </div>
    </div>
</body>
</html>
