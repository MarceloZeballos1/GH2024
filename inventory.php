<?php
require 'includes/db.php';
require 'includes/auth.php';

// Obtener todos los equipos de la base de datos
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Inventario de Equipos</h1>
        <table class="table table-bordered mt-4 table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Nro de Serie</th>
                    <th>Unidad</th>
                    <th>Gestión de Ingreso</th>
                    <th>Tipo de Equipo</th>
                    <th>Características Físicas</th>
                    <th>Estado</th>
                    <th>Requisitos de Funcionamiento</th>
                    <th>Alimentación Eléctrica</th>
                    <th>Proveedor de Servicio de Mantenimiento</th>
                    <th>Proveedor de Compra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?= htmlspecialchars($equipment['id']) ?></td>
                    <td><?= htmlspecialchars($equipment['name']) ?></td>
                    <td><?= htmlspecialchars($equipment['marca']) ?></td>
                    <td><?= htmlspecialchars($equipment['modelo']) ?></td>
                    <td><?= htmlspecialchars($equipment['nro_serie']) ?></td>
                    <td><?= htmlspecialchars($equipment['unidad']) ?></td>
                    <td><?= htmlspecialchars($equipment['gestion_ingreso']) ?></td>
                    <td><?= htmlspecialchars($equipment['tipo_equipo']) ?></td>
                    <td><?= htmlspecialchars($equipment['caracteristicas_fisicas']) ?></td>
                    <td><?= htmlspecialchars($equipment['status']) ?></td>
                    <td><?= htmlspecialchars($equipment['requisitos_funcionamiento']) ?></td>
                    <td><?= htmlspecialchars($equipment['alimentacion_electrica']) ?></td>
                    <td><?= htmlspecialchars($equipment['proveedor_servicio_mantenimiento']) ?></td>
                    <td><?= htmlspecialchars($equipment['proveedor_compra']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
