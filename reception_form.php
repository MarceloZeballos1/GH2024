<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';
require 'includes/header.php';

// Procesar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment_id = $_POST['equipment_id'];
    $date = $_POST['date'];
    $name = $_POST['name'];
    $equipment = $_POST['equipment'];
    $category = $_POST['category'];
    $unit = $_POST['unit'];
    $note = $_POST['note'];
    $inventory_number = $_POST['inventory_number'];
    $model_number = $_POST['model_number'];
    $serial_number = $_POST['serial_number'];
    $supplier = $_POST['supplier'];
    $manufacturer = $_POST['manufacturer'];
    $functional_units = $_POST['functional_units'];
    $function_score = $_POST['function_score'];
    $risk_score = $_POST['risk_score'];
    $maintenance_score = $_POST['maintenance_score'];
    $reception_date = $_POST['reception_date'];
    $installation_date = $_POST['installation_date'];
    $warranty_date = $_POST['warranty_date'];
    $purchase_price = $_POST['purchase_price'];
    $lifespan = $_POST['lifespan'];
    $mp_schedule = $_POST['mp_schedule'];
    $service_order = $_POST['service_order'];
    $observations = $_POST['observations'];

    $stmt = $conn->prepare("INSERT INTO equipment_reception (equipment_id, date, name, equipment, category, unit, note, inventory_number, model_number, serial_number, supplier, manufacturer, functional_units, function_score, risk_score, maintenance_score, reception_date, installation_date, warranty_date, purchase_price, lifespan, mp_schedule, service_order, observations) 
                            VALUES (:equipment_id, :date, :name, :equipment, :category, :unit, :note, :inventory_number, :model_number, :serial_number, :supplier, :manufacturer, :functional_units, :function_score, :risk_score, :maintenance_score, :reception_date, :installation_date, :warranty_date, :purchase_price, :lifespan, :mp_schedule, :service_order, :observations)");
    $stmt->execute([
        'equipment_id' => $equipment_id,
        'date' => $date,
        'name' => $name,
        'equipment' => $equipment,
        'category' => $category,
        'unit' => $unit,
        'note' => $note,
        'inventory_number' => $inventory_number,
        'model_number' => $model_number,
        'serial_number' => $serial_number,
        'supplier' => $supplier,
        'manufacturer' => $manufacturer,
        'functional_units' => $functional_units,
        'function_score' => $function_score,
        'risk_score' => $risk_score,
        'maintenance_score' => $maintenance_score,
        'reception_date' => $reception_date,
        'installation_date' => $installation_date,
        'warranty_date' => $warranty_date,
        'purchase_price' => $purchase_price,
        'lifespan' => $lifespan,
        'mp_schedule' => $mp_schedule,
        'service_order' => $service_order,
        'observations' => $observations,
    ]);

    $success_message = "Recepción de equipo registrada exitosamente.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepción de Equipos Nuevos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Recepción de Equipos Nuevos</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="equipment_id" class="form-label">ID</label>
                <input type="text" class="form-control" id="equipment_id" name="equipment_id" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="equipment" class="form-label">Equipo</label>
                <input type="text" class="form-control" id="equipment" name="equipment" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unidad</label>
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>

            <h5>Detalles</h5>
            <div class="mb-3">
                <label for="note" class="form-label">Nota</label>
                <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="inventory_number" class="form-label">No de Inventario</label>
                <input type="text" class="form-control" id="inventory_number" name="inventory_number" required>
            </div>
            <div class="mb-3">
                <label for="model_number" class="form-label">Modelo#</label>
                <input type="text" class="form-control" id="model_number" name="model_number" required>
            </div>
            <div class="mb-3">
                <label for="serial_number" class="form-label">No de Serie</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
            </div>
            <div class="mb-3">
                <label for="supplier" class="form-label">Proveedor</label>
                <input type="text" class="form-control" id="supplier" name="supplier" required>
            </div>
            <div class="mb-3">
                <label for="manufacturer" class="form-label">Fabricante</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
            </div>
            <div class="mb-3">
                <label for="functional_units" class="form-label">Unidades Funcionales</label>
                <input type="number" class="form-control" id="functional_units" name="functional_units" required>
            </div>
            <div class="mb-3">
                <label for="function_score" class="form-label">Puntuación de Función</label>
                <select class="form-select" id="function_score" name="function_score" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="risk_score" class="form-label">Puntuación de Riesgo</label>
                <select class="form-select" id="risk_score" name="risk_score" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="maintenance_score" class="form-label">Puntuación de Mantenimiento</label>
                <select class="form-select" id="maintenance_score" name="maintenance_score" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <h5>Información sobre la adquisición</h5>
            <div class="mb-3">
                <label for="reception_date" class="form-label">Fecha de Recepción</label>
                <input type="date" class="form-control" id="reception_date" name="reception_date" required>
            </div>
            <div class="mb-3">
                <label for="installation_date" class="form-label">Fecha de Instalación</label>
                <input type="date" class="form-control" id="installation_date" name="installation_date" required>
            </div>
            <div class="mb-3">
                <label for="warranty_date" class="form-label">Fecha de la Garantía</label>
                <input type="date" class="form-control" id="warranty_date" name="warranty_date" required>
            </div>
            <div class="mb-3">
                <label for="purchase_price" class="form-label">Precio de Compra</label>
                <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" required>
            </div>
            <div class="mb-3">
                <label for="lifespan" class="form-label">Vida Útil (en años)</label>
                <input type="number" class="form-control" id="lifespan" name="lifespan" required>
            </div>
            <div class="mb-3">
                <label for="mp_schedule" class="form-label">Cronograma de MP</label>
                <input type="text" class="form-control" id="mp_schedule" name="mp_schedule" required>
            </div>
            <div class="mb-3">
                <label for="service_order" class="form-label">Orden de Servicio</label>
                <input type="text" class="form-control" id="service_order" name="service_order" required>
            </div>
            <div class="mb-3">
                <label for="observations" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Recepción</button>
        </form>
    </div>
</body>
</html>
