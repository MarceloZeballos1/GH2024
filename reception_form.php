<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo_medico = $_POST['equipo_medico'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $nro_serie = $_POST['nro_serie'];
    $unidad = $_POST['unidad'];
    $gestion_ingreso = $_POST['gestion_ingreso'];
    $tipo_equipo = $_POST['tipo_equipo'];
    $caracteristicas_fisicas = $_POST['caracteristicas_fisicas'];
    $estado = $_POST['estado'];
    $ubicacion = $_POST['location'];  // Nuevo campo para ubicación
    $requisitos_funcionamiento = $_POST['requisitos_funcionamiento'];
    $alimentacion_electrica = $_POST['alimentacion_electrica'];
    $proveedor_mantenimiento = $_POST['proveedor_mantenimiento'];
    $proveedor_compra = $_POST['proveedor_compra'];

    $stmt = $conn->prepare("
    INSERT INTO equipments 
    (name, marca, modelo, nro_serie, unidad, gestion_ingreso, tipo_equipo, caracteristicas_fisicas, estado, location, requisitos_funcionamiento, alimentacion_electrica, proveedor_servicio_mantenimiento, proveedor_compra)
    VALUES 
    (:name, :marca, :modelo, :nro_serie, :unidad, :gestion_ingreso, :tipo_equipo, :caracteristicas_fisicas, :estado, :ubicacion, :requisitos_funcionamiento, :alimentacion_electrica, :proveedor_mantenimiento, :proveedor_compra)
    ");

    $stmt->execute([
        'name' => $equipo_medico,
        'marca' => $marca,
        'modelo' => $modelo,
        'nro_serie' => $nro_serie,
        'unidad' => $unidad,
        'gestion_ingreso' => $gestion_ingreso,
        'tipo_equipo' => $tipo_equipo,
        'caracteristicas_fisicas' => $caracteristicas_fisicas,
        'estado' => $estado,
        'ubicacion' => $location,  // Inserción de ubicación
        'requisitos_funcionamiento' => $requisitos_funcionamiento,
        'alimentacion_electrica' => $alimentacion_electrica,
        'proveedor_mantenimiento' => $proveedor_mantenimiento,
        'proveedor_compra' => $proveedor_compra,
    ]);

    $success_message = "Equipo médico registrado exitosamente.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Equipos Médicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Registro de Equipos Médicos</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Equipo Médico</label>
                <input type="text" class="form-control" id="name" name="equipo_medico" required>
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" required>
            </div>
            <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required>
            </div>
            <div class="mb-3">
                <label for="nro_serie" class="form-label">Nro. de Serie</label>
                <input type="text" class="form-control" id="nro_serie" name="nro_serie" required>
            </div>
            <div class="mb-3">
                <label for="unidad" class="form-label">Unidad</label>
                <input type="text" class="form-control" id="unidad" name="unidad" required>
            </div>
            <div class="mb-3">
                <label for="gestion_ingreso" class="form-label">Gestión de Ingreso</label>
                <input type="number" class="form-control" id="gestion_ingreso" name="gestion_ingreso" required>
            </div>
            <div class="mb-3">
                <label for="tipo_equipo" class="form-label">Tipo de Equipo</label>
                <select class="form-select" id="tipo_equipo" name="tipo_equipo" required>
                    <option value="Terapeutico">Terapéutico</option>
                    <option value="Diagnostico">Diagnóstico</option>
                    <option value="Analitico">Analítico</option>
                    <option value="De soporte vital">De soporte vital</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="caracteristicas_fisicas" class="form-label">Características Físicas</label>
                <textarea class="form-control" id="caracteristicas_fisicas" name="caracteristicas_fisicas" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Operativo">Operativo</option>
                    <option value="En reparación">En reparación</option>
                    <option value="Fuera de servicio">Fuera de servicio</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="requisitos_funcionamiento" class="form-label">Requisitos de Funcionamiento</label>
                <textarea class="form-control" id="requisitos_funcionamiento" name="requisitos_funcionamiento" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="alimentacion_electrica" class="form-label">Alimentación Eléctrica</label>
                <select class="form-select" id="alimentacion_electrica" name="alimentacion_electrica" required>
                    <option value="110V">110V</option>
                    <option value="220V">220V</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="proveedor_mantenimiento" class="form-label">Proveedor de Mantenimiento</label>
                <input type="text" class="form-control" id="proveedor_mantenimiento" name="proveedor_mantenimiento" required>
            </div>
            <div class="mb-3">
                <label for="proveedor_compra" class="form-label">Proveedor de Compra</label>
                <input type="text" class="form-control" id="proveedor_compra" name="proveedor_compra" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Equipo</button>
        </form>
    </div>
</body>
</html>
