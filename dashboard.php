<?php
require 'includes/db.php';
require 'includes/auth.php';

// Consultas de datos
$equipments_total = $conn->query("SELECT COUNT(*) FROM equipments")->fetchColumn();
$orders_active = $conn->query("SELECT COUNT(*) FROM work_orders WHERE status != 'Completado'")->fetchColumn(); // Cambié el estado para reflejar el ENUM definido
$maintenance_alerts = $conn->query("SELECT COUNT(*) FROM maintenance_schedule WHERE DATE(schedule_date) <= CURDATE()")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Dashboard</h1>
        <div class="row my-4">
            <div class="col-md-4">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5>Total Equipos</h5>
                        <h3><?= $equipments_total ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5>Órdenes Activas</h5>
                        <h3><?= $orders_active ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-danger">
                    <div class="card-body">
                        <h5>Alertas de Mantenimiento</h5>
                        <h3><?= $maintenance_alerts ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
