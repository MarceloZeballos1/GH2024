<?php
header('Content-Type: text/html; charset=utf-8');
require 'includes/db.php';
require 'includes/auth.php';

// Consultas de datos
$equipments_total = $conn->query("SELECT COUNT(*) FROM equipments")->fetchColumn();
$orders_active = $conn->query("SELECT COUNT(*) FROM work_orders WHERE status != 'Completado'")->fetchColumn(); 
$maintenance_alerts = $conn->query("SELECT COUNT(*) FROM maintenance_schedule WHERE DATE(schedule_date) <= CURDATE() AND status = 'Pendiente'")->fetchColumn();

// Consultas para obtener los equipos y su mantenimiento
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();
$maintenance_schedule = $conn->query("SELECT * FROM maintenance_schedule WHERE status = 'Pendiente'")->fetchAll();
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
        <h1 class="mb-4">Dashboard de Mantenimiento</h1>
        
        <!-- Información General -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5>Total Equipos</h5>
                        <h3 class="display-4"><?= $equipments_total ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5>Órdenes Activas</h5>
                        <h3 class="display-4"><?= $orders_active ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-danger">
                    <div class="card-body">
                        <h5>Alertas de Mantenimiento</h5>
                        <h3 class="display-4"><?= $maintenance_alerts ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipos y su Mantenimiento -->
        <h2 class="mb-3">Equipos y su Mantenimiento</h2>
        <div class="row">
            <?php foreach ($equipments as $equipment): ?>
                <div class="col-md-4 mb-4">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title"><?= $equipment['name'] ?></h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Status:</strong> <?= $equipment['status'] ?></p>
                            <p><strong>Ubicación:</strong> <?= $equipment['location'] ?></p>

                            <?php
                                // Obtener el próximo mantenimiento del equipo
                                $maintenance = array_filter($maintenance_schedule, function($m) use ($equipment) {
                                    return $m['equipment_id'] == $equipment['id'];
                                });
                                if (count($maintenance) > 0) {
                                    $maintenance = reset($maintenance); // Tomar el primer elemento
                                    echo "<p><strong>Próximo Mantenimiento:</strong> {$maintenance['schedule_date']}</p>";
                                } else {
                                    echo "<p>No se ha programado mantenimiento.</p>";
                                }
                            ?>
                        </div>
                        <div class="card-footer text-muted">
                            <small>Última actualización: <?= date('d M Y', strtotime($equipment['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
