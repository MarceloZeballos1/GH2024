<?php
require 'includes/db.php';
require 'includes/auth.php';

$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Inventario de Equipos</h1>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Ubicación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?= $equipment['id'] ?></td>
                    <td><?= $equipment['name'] ?></td>
                    <td><?= $equipment['status'] ?></td>
                    <td><?= $equipment['location'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
