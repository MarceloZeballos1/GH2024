<?php
session_start();
require 'db.php'; // Conexión a la base de datos
require 'includes/auth.php'; // Verificar autenticación

// Consulta para indicadores clave
$equipments_total = $conn->query("SELECT COUNT(*) AS total FROM equipments")->fetch()['total'];
$orders_active = $conn->query("SELECT COUNT(*) AS active FROM work_orders WHERE status != 'completed'")->fetch()['active'];
$maintenance_alerts = $conn->query("SELECT COUNT(*) AS alerts FROM maintenance_schedule WHERE DATE(next_maintenance) <= CURDATE()")->fetch()['alerts'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>

        <!-- Indicadores clave -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total de Equipos</h5>
                        <p class="card-text fs-4"><?= $equipments_total ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Órdenes Activas</h5>
                        <p class="card-text fs-4"><?= $orders_active ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Alertas de Mantenimiento</h5>
                        <p class="card-text fs-4"><?= $maintenance_alerts ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="equipmentStatusChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="ordersStatusChart"></canvas>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Datos para los gráficos
        const equipmentStatusData = {
            labels: ['Operativos', 'En Reparación', 'Retirados'],
            datasets: [{
                data: [50, 15, 5], // Ejemplo: Reemplazar con datos reales de PHP
                backgroundColor: ['#007bff', '#ffc107', '#dc3545']
            }]
        };

        const ordersStatusData = {
            labels: ['Pendientes', 'En Proceso', 'Completadas'],
            datasets: [{
                data: [30, 10, 60], // Ejemplo: Reemplazar con datos reales de PHP
                backgroundColor: ['#ffc107', '#007bff', '#28a745']
            }]
        };

        // Configuración de gráficos
        const equipmentStatusChart = new Chart(
            document.getElementById('equipmentStatusChart'),
            { type: 'pie', data: equipmentStatusData }
        );

        const ordersStatusChart = new Chart(
            document.getElementById('ordersStatusChart'),
            { type: 'doughnut', data: ordersStatusData }
        );
    </script>
</body>
</html>
