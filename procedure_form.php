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
    $equipment_type = $_POST['equipment_type'];
    $equipment_name = $_POST['equipment_name'];
    $risk_score = $_POST['risk_score'];
    $safety_inspections = $_POST['safety_inspections'];
    $mp_verifications = $_POST['mp_verifications'];
    $procedure_details = $_POST['procedure_details'];

    // Consulta SQL corregida
    $stmt = $conn->prepare("INSERT INTO procedure_forms 
        (equipment_id, type, equipment_name, risk_score, safety_inspections_per_year, maintenance_verifications_per_year, procedures) 
        VALUES 
        (:equipment_id, :type, :equipment_name, :risk_score, :safety_inspections_per_year, :maintenance_verifications_per_year, :procedures)");

    $stmt->execute([
        'equipment_id' => $equipment_id,
        'type' => $equipment_type,
        'equipment_name' => $equipment_name,
        'risk_score' => $risk_score,
        'safety_inspections_per_year' => $safety_inspections,
        'maintenance_verifications_per_year' => $mp_verifications,
        'procedures' => $procedure_details,
    ]);

    $success_message = "Modelo de procedimiento registrado exitosamente.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelo de Procedimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Modelo de Procedimiento</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="equipment_id" class="form-label">ID</label>
                <input type="text" class="form-control" id="equipment_id" name="equipment_id" required>
            </div>
            <div class="mb-3">
                <label for="equipment_type" class="form-label">Tipo de Equipo</label>
                <input type="text" class="form-control" id="equipment_type" name="equipment_type" required>
            </div>
            <div class="mb-3">
                <label for="equipment_name" class="form-label">Nombre y/o Tipo de Equipo</label>
                <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
            </div>
            <div class="mb-3">
                <label for="risk_score" class="form-label">Puntuación de Riesgo</label>
                <select class="form-select" id="risk_score" name="risk_score" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="safety_inspections" class="form-label">Inspecciones de Seguridad/año</label>
                <input type="number" class="form-control" id="safety_inspections" name="safety_inspections" required>
            </div>
            <div class="mb-3">
                <label for="mp_verifications" class="form-label">Verificaciones MP/año</label>
                <input type="text" class="form-control" id="mp_verifications" name="mp_verifications" required>
            </div>
            <div class="mb-3">
                <label for="procedure_details" class="form-label">Procedimiento</label>
                <textarea class="form-control" id="procedure_details" name="procedure_details" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Procedimiento</button>
        </form>
    </div>
</body>
</html>
