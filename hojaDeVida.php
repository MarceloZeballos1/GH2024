<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php'; // Incluye la conexión a la base de datos
require 'includes/header.php'; // Incluye el encabezado común, si lo tienes

// Lógica para filtrar por ID
$filter_id = isset($_GET['id_filter']) ? trim($_GET['id_filter']) : '';

// Consultar las órdenes de servicio
$query = "SELECT * FROM service_orders";
if (!empty($filter_id)) {
    $query .= " WHERE id LIKE :filter_id";
}

$stmt = $conn->prepare($query);
if (!empty($filter_id)) {
    $stmt->bindValue(':filter_id', '%' . $filter_id . '%', PDO::PARAM_STR);
}
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Vida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
        .filter-form {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .filter-form .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .filter-form .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .header-text {
            color: #343a40;
            font-weight: bold;
        }
        .no-results {
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center header-text">Hoja de Vida - Órdenes de Servicio</h1>

        <!-- Formulario para filtrar por ID -->
        <div class="filter-form mb-4">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label for="id_filter" class="form-label">Filtrar por ID</label>
                    <input type="text" class="form-control" id="id_filter" name="id_filter" placeholder="Ingrese el ID" value="<?= htmlspecialchars($filter_id) ?>">
                </div>
                <div class="col-md-6 text-md-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="hojaDeVida.php" class="btn btn-secondary">Limpiar Filtro</a>
                </div>
            </form>
        </div>

        <!-- Tabla para mostrar las órdenes de servicio -->
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="table ">
                    <tr>
                        <th>ID</th>
                        <th>Unidad</th>
                        <th>Fecha Hora</th>
                        <th>Clínico/Técnico</th>
                        <th>Ubicación</th>
                        <th>Descripción del Problema</th>
                        <th>Ingeniero Asignado</th>
                        <th>Fecha de Respuesta</th>
                        <th>Tarea Realizada</th>
                        <th>¿Resuelto?</th>
                        <th>¿Tareas Adicionales?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars($order['department']) ?></td>
                                <td><?= htmlspecialchars($order['date']) ?></td>
                                <td><?= htmlspecialchars($order['technician_name']) ?></td>
                                <td><?= htmlspecialchars($order['device_location']) ?></td>
                                <td><?= htmlspecialchars($order['problem_description']) ?></td>
                                <td><?= htmlspecialchars($order['engineer_name']) ?></td>
                                <td><?= htmlspecialchars($order['response_date']) ?></td>
                                <td><?= htmlspecialchars($order['task_performed']) ?></td>
                                <td><?= htmlspecialchars($order['resolved'] ? 'Sí' : 'No') ?></td>
                                <td><?= htmlspecialchars($order['additional_tasks'] ? 'Sí' : 'No') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center no-results">No se encontraron órdenes de servicio</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
