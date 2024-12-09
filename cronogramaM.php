<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';  // Asumiendo que esta es tu conexión a la base de datos
require 'includes/header.php'; // Incluye el encabezado común, si lo tienes

// Obtener los equipos de la base de datos
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();

// Manejo de la actualización del cronograma de mantenimiento preventivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_schedule'])) {
    $equipment_id = $_POST['equipment_id'];
    $maintenance_type = $_POST['maintenance_type'];
    $maintenance_schedule = $_POST['maintenance_schedule'];
    $maintenance_responsible = $_POST['maintenance_responsible'];
    $replacement_parts_needed = $_POST['replacement_parts_needed'];
    $maintenance_date = $_POST['maintenance_date']; // Fecha de mantenimiento

    // Verificar si el tipo de mantenimiento es preventivo
    if ($maintenance_type === 'preventive') {
        // Insertar o actualizar el cronograma de mantenimiento preventivo en la tabla `maintenance_schedule`
        $stmt = $conn->prepare("INSERT INTO maintenance_schedule (equipment_id, schedule_date, next_maintenance, description, status) 
                                VALUES (:equipment_id, :schedule_date, :next_maintenance, :description, 'Pendiente')");
        $stmt->execute([
            'equipment_id' => $equipment_id,
            'schedule_date' => $maintenance_date,
            'next_maintenance' => $maintenance_schedule,  // Fecha del próximo mantenimiento
            'description' => $replacement_parts_needed,
        ]);
        $success_message = "Cronograma de mantenimiento preventivo actualizado exitosamente.";
    } else {
        // Si es mantenimiento correctivo, se debe actualizar la tabla `maintenance_schedule` con el mantenimiento correcto
        $stmt = $conn->prepare("INSERT INTO maintenance_schedule (equipment_id, schedule_date, next_maintenance, description, status) 
                                VALUES (:equipment_id, :schedule_date, NULL, :description, 'Pendiente')");
        $stmt->execute([
            'equipment_id' => $equipment_id,
            'schedule_date' => $maintenance_date,
            'description' => $replacement_parts_needed,
        ]);
        $success_message = "Mantenimiento correctivo registrado. Cronograma preventivo eliminado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma de Mantenimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <h1 class="mb-4">Cronograma de Mantenimiento</h1>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="equipment_id" class="form-label">Seleccionar Equipo</label>
                <select class="form-select" id="equipment_id" name="equipment_id" required>
                    <?php foreach ($equipments as $equipment): ?>
                        <option value="<?= $equipment['id']; ?>"><?= $equipment['name']; ?> (<?= $equipment['status']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="maintenance_type" class="form-label">Tipo de Mantenimiento</label>
                <select class="form-select" id="maintenance_type" name="maintenance_type" required>
                    <option value="corrective">Mantenimiento Correctivo</option>
                    <option value="preventive">Mantenimiento Preventivo</option>
                </select>
            </div>

            <div class="mb-3" id="preventive_schedule_div" style="display:none;">
                <label for="maintenance_schedule" class="form-label">Intervalo de Mantenimiento Preventivo</label>
                <select class="form-select" id="maintenance_schedule" name="maintenance_schedule">
                    <option value="Mensual">Mensual</option>
                    <option value="Trimestral">Trimestral</option>
                    <option value="Semestral">Semestral</option>
                    <option value="Anual">Anual</option>
                </select>
            </div>

            <div class="mb-3" id="maintenance_date_div">
                <label for="maintenance_date" class="form-label">Fecha de Mantenimiento</label>
                <input type="date" class="form-control" id="maintenance_date" name="maintenance_date" required>
            </div>

            <div class="mb-3">
                <label for="maintenance_responsible" class="form-label">Responsable de Mantenimiento</label>
                <input type="text" class="form-control" id="maintenance_responsible" name="maintenance_responsible" required>
            </div>

            <div class="mb-3">
                <label for="replacement_parts_needed" class="form-label">¿Se necesitarán repuestos o componentes?</label>
                <textarea class="form-control" id="replacement_parts_needed" name="replacement_parts_needed" rows="3" placeholder="Describa los repuestos o componentes necesarios si aplica..."></textarea>
            </div>

            <button type="submit" name="update_schedule" class="btn btn-primary">Actualizar Cronograma</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar el campo de cronograma solo si el mantenimiento es preventivo
        document.getElementById('maintenance_type').addEventListener('change', function() {
            var preventiveScheduleDiv = document.getElementById('preventive_schedule_div');
            if (this.value === 'preventive') {
                preventiveScheduleDiv.style.display = 'block';
            } else {
                preventiveScheduleDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>
