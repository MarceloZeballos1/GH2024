<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/header.php';
require 'includes/db.php'; // Conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-button {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 20px;
            text-decoration: none;
            color: white;
            display: block;
            height: 100%;
        }
        .card-button:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(145deg, #007bff, #0056b3);
            border: none;
        }

        .btn-success {
            background: linear-gradient(145deg, #28a745, #218838);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(145deg, #ffc107, #e0a800);
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4 text-center text-dark">Gestión de Reportes</h1>
        
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <a href="reception_form.php" class="card-button btn-primary">
                    <h4>Formulario de Recepción</h4>
                    <p>Accede al formulario de recepción para registrar equipos.</p>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="procedure_form.php" class="card-button btn-success">
                    <h4>Modelo de Procedimiento</h4>
                    <p>Accede al modelo de procedimiento para gestionar los procesos de mantenimiento.</p>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="service_order_form.php" class="card-button btn-warning">
                    <h4>Formulario de Orden de Servicio</h4>
                    <p>Accede al formulario de orden de servicio para reportar problemas.</p>
                </a>
            </div>
        </div>

        <!-- Tabla para mostrar registros -->
        <h2 class="mb-4 text-dark">Modelos de Procedimientos Registrados</h2>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo de Equipo</th>
                    <th>Nombre del Equipo</th>
                    <th>Puntuación de Riesgo</th>
                    <th>Inspecciones/año</th>
                    <th>Verificaciones MP/año</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM procedure_forms");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                    <td><?= htmlspecialchars($row['risk_score']) ?></td>
                    <td><?= htmlspecialchars($row['safety_inspections_per_year']) ?></td>
                    <td><?= htmlspecialchars($row['maintenance_verifications_per_year']) ?></td>
                    <td><?= htmlspecialchars($row['procedures']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
