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
    $service_id = $_POST['service_id'];
    $unit = $_POST['unit'];
    $date_time = $_POST['date_time'];
    $reporter = $_POST['reporter'];
    $device_location = $_POST['device_location'];
    $problem_description = $_POST['problem_description'];

    $engineer_name = $_POST['engineer_name'];
    $response_date_time = $_POST['response_date_time'];
    $task_done = $_POST['task_done'];
    $problem_solved = $_POST['problem_solved'];
    $additional_tasks_needed = $_POST['additional_tasks_needed'];
    $additional_work_date = $_POST['additional_work_date'];

    $followup_engineer_name = $_POST['followup_engineer_name'];
    $followup_date_time = $_POST['followup_date_time'];
    $followup_tasks_done = $_POST['followup_tasks_done'];
    $followup_problem_solved = $_POST['followup_problem_solved'];
    $further_tasks_needed = $_POST['further_tasks_needed'];

    $stmt = $conn->prepare("INSERT INTO service_orders (equipment_id, department, date, technician_name, device_location, problem_description, response_date, engineer_name, task_performed, resolved, additional_tasks) 
                        VALUES (:equipment_id, :department, :date, :technician_name, :device_location, :problem_description, :response_date, :engineer_name, :task_performed, :resolved, :additional_tasks)");
$stmt->execute([
    'equipment_id' => $service_id, // Aquí usas la variable service_id, pero ahora se mapea a equipment_id
    'department' => $unit,  // Ajusta si necesario según la estructura de tu tabla
    'date' => $date_time,  // Se usa el campo date que está en la tabla
    'technician_name' => $reporter, // O puedes mapearlo con otro campo si es necesario
    'device_location' => $device_location,
    'problem_description' => $problem_description,
    'response_date' => $response_date_time,
    'engineer_name' => $engineer_name,
    'task_performed' => $task_done,
    'resolved' => ($problem_solved == "Sí") ? 1 : 0, // Para convertir Sí/No a 1/0
    'additional_tasks' => $additional_tasks_needed,
]);


    $success_message = "Orden de servicio registrada exitosamente.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Orden de Servicio</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <h5>Solicitud de Servicios</h5>
            <div class="mb-3">
                <label for="service_id" class="form-label">ID</label>
                <input type="text" class="form-control" id="service_id" name="service_id" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unidad</label>
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>
            <div class="mb-3">
                <label for="date_time" class="form-label">Fecha y Hora</label>
                <input type="datetime-local" class="form-control" id="date_time" name="date_time" required>
            </div>
            <div class="mb-3">
                <label for="reporter" class="form-label">Clínico / Técnico que informó el problema</label>
                <input type="text" class="form-control" id="reporter" name="reporter" required>
            </div>
            <div class="mb-3">
                <label for="device_location" class="form-label">Ubicación del Dispositivo</label>
                <input type="text" class="form-control" id="device_location" name="device_location" required>
            </div>
            <div class="mb-3">
                <label for="problem_description" class="form-label">Descripción del Problema</label>
                <textarea class="form-control" id="problem_description" name="problem_description" rows="3" required></textarea>
            </div>

            <h5>Registro de Servicio</h5>
            <div class="mb-3">
                <label for="engineer_name" class="form-label">Nombre del Ingeniero</label>
                <select class="form-select" id="engineer_name" name="engineer_name" required>
                    <?php
                    // Cargar todos los usuarios, no solo los de tipo "engineer"
                    $users = $conn->query("SELECT username FROM users")->fetchAll();
                    foreach ($users as $user):
                    ?>
                        <option value="<?= $user['username']; ?>"><?= $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="response_date_time" class="form-label">Fecha y Hora de Respuesta</label>
                <input type="datetime-local" class="form-control" id="response_date_time" name="response_date_time" required>
            </div>
            <div class="mb-3">
                <label for="task_done" class="form-label">Tarea Realizada</label>
                <textarea class="form-control" id="task_done" name="task_done" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="problem_solved" class="form-label">¿Se resolvió el problema?</label>
                <select class="form-select" id="problem_solved" name="problem_solved" required>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="additional_tasks_needed" class="form-label">¿Es necesario realizar tareas adicionales?</label>
                <select class="form-select" id="additional_tasks_needed" name="additional_tasks_needed" required>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="additional_work_date" class="form-label">¿Cuándo se realizará el trabajo adicional?</label>
                <input type="date" class="form-control" id="additional_work_date" name="additional_work_date">
            </div>

            <h5>Seguimiento</h5>
            <div class="mb-3">
                <label for="followup_engineer_name" class="form-label">Nombre del Ingeniero</label>
                <select class="form-select" id="followup_engineer_name" name="followup_engineer_name" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['username']; ?>"><?= $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="followup_date_time" class="form-label">Fecha y Hora de Respuesta</label>
                <input type="datetime-local" class="form-control" id="followup_date_time" name="followup_date_time">
            </div>
            <div class="mb-3">
                <label for="followup_tasks_done" class="form-label">Tareas Realizadas</label>
                <textarea class="form-control" id="followup_tasks_done" name="followup_tasks_done" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="followup_problem_solved" class="form-label">¿Se resolvió el problema?</label>
                <select class="form-select" id="followup_problem_solved" name="followup_problem_solved">
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="further_tasks_needed" class="form-label">¿Se necesitan más tareas?</label>
                <select class="form-select" id="further_tasks_needed" name="further_tasks_needed">
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Orden</button>
        </form>
    </div>
</body>
</html>
