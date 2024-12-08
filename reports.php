<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/header.php'; // Incluye el encabezado común, si lo tienes

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
            position: relative;
        }

        .card-button:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.15);
        }

        .card-button h4 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .card-button p {
            font-size: 1rem;
            margin-bottom: 0;
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
        
        <div class="row">
            <!-- Botón para Formulario de Recepción -->
            <div class="col-md-4 mb-3">
                <a href="reception_form.php" class="card-button btn-primary">
                    <h4>Formulario de Recepción</h4>
                    <p>Accede al formulario de recepción para registrar equipos.</p>
                </a>
            </div>

            <!-- Botón para Modelo de Procedimiento -->
            <div class="col-md-4 mb-3">
                <a href="procedure_form.php" class="card-button btn-success">
                    <h4>Modelo de Procedimiento</h4>
                    <p>Accede al modelo de procedimiento para gestionar los procesos de mantenimiento.</p>
                </a>
            </div>

            <!-- Botón para Formulario de Orden de Servicio -->
            <div class="col-md-4 mb-3">
                <a href="service_order_form.php" class="card-button btn-warning">
                    <h4>Formulario de Orden de Servicio</h4>
                    <p>Accede al formulario de orden de servicio para reportar problemas.</p>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
