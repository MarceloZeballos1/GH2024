<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php'; // Incluye la conexión a la base de datos
require 'includes/header.php'; // Incluye el encabezado común, si lo tienes

// Consultas para obtener estadísticas
$query_total_orders = "SELECT COUNT(*) as total_orders FROM service_orders";
$query_resolved_orders = "SELECT COUNT(*) as resolved_orders FROM service_orders WHERE resolved = 1";
$query_unresolved_orders = "SELECT COUNT(*) as unresolved_orders FROM service_orders WHERE resolved = 0";
$query_orders_by_department = "SELECT department, COUNT(*) as order_count FROM service_orders GROUP BY department";
$query_orders_by_technician = "SELECT technician_name, COUNT(*) as order_count FROM service_orders GROUP BY technician_name";

// Ejecutando las consultas con la variable $conn
$total_orders = $conn->query($query_total_orders)->fetch(PDO::FETCH_ASSOC)['total_orders'];
$resolved_orders = $conn->query($query_resolved_orders)->fetch(PDO::FETCH_ASSOC)['resolved_orders'];
$unresolved_orders = $conn->query($query_unresolved_orders)->fetch(PDO::FETCH_ASSOC)['unresolved_orders'];
$orders_by_department = $conn->query($query_orders_by_department)->fetchAll(PDO::FETCH_ASSOC);
$orders_by_technician = $conn->query($query_orders_by_technician)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Estadísticas de Órdenes de Servicio</h2>

        <!-- Estadísticas generales -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Órdenes</h5>
                        <p class="card-text"><?= $total_orders ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Órdenes Resueltas</h5>
                        <p class="card-text"><?= $resolved_orders ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Órdenes No Resueltas</h5>
                        <p class="card-text"><?= $unresolved_orders ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Órdenes por departamento -->
        <h4 class="mt-5">Órdenes por Departamento</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Total Órdenes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders_by_department as $order): ?>
                    <tr>
                        <td><?= $order['department'] ?></td>
                        <td><?= $order['order_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Órdenes por técnico -->
        <h4 class="mt-5">Órdenes por Técnico</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Técnico</th>
                    <th>Total Órdenes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders_by_technician as $order): ?>
                    <tr>
                        <td><?= $order['technician_name'] ?></td>
                        <td><?= $order['order_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
