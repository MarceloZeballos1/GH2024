<?php
require 'includes/db.php';
require 'includes/auth.php';

// Reporte simple: total de equipos por estado
$equipments_report = $conn->query("SELECT status, COUNT(*) AS total FROM equipments GROUP BY status")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
    <h1>Reportes</h1>
    <h3>Total de Equipos por Estado</h3>
    <canvas id="equipmentsChart" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('equipmentsChart').getContext('2d');
        const data = {
            labels: <?= json_encode(array_column($equipments_report, 'status')) ?>,
            datasets: [{
                label: 'Total de Equipos',
                data: <?= json_encode(array_column($equipments_report, 'total')) ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            }]
        };
        const config = {
            type: 'bar',
            data: data,
        };
        new Chart(ctx, config);
    </script>
</div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
