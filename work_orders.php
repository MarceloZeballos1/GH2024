<?php
session_start();
require 'includes/auth.php'; // Verifica si el usuario está autenticado
require 'includes/db.php';

// Crear orden de trabajo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipment_id = $_POST['equipment_id'];
    $description = $_POST['description'];

    // Insertar orden de trabajo en la base de datos
    $stmt = $db->prepare("INSERT INTO work_orders (user_id, equipment_id, description, status) VALUES (:user_id, :equipment_id, :description, 'pending')");
    $stmt->execute([
        'user_id' => $_SESSION['user']['id'],
        'equipment_id' => $equipment_id,
        'description' => $description
    ]);
}

// Obtener todas las órdenes de trabajo
$orders = $db->query("SELECT * FROM work_orders ORDER BY created_at DESC")->fetchAll();

// Obtener equipos disponibles para la selección en el formulario
$equipments = $db->query("SELECT * FROM equipments WHERE status = 'active'")->fetchAll();  // Filtrar solo los equipos activos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de Trabajo - HRSJDD</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
        main {
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1>Órdenes de Trabajo</h1>
        
        <!-- Formulario para crear una orden de trabajo -->
        <section>
            <h2>Crear Nueva Orden de Trabajo</h2>
            <form method="POST">
                <label for="equipment_id">Seleccionar Equipo:</label>
                <select name="equipment_id" id="equipment_id" required>
                    <option value="">Selecciona un equipo</option>
                    <?php foreach ($equipments as $equipment): ?>
                        <option value="<?php echo $equipment['id']; ?>">
                            <?php echo $equipment['name'] . ' - ' . $equipment['type']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="description">Descripción:</label>
                <textarea name="description" id="description" required></textarea>
                
                <button type="submit">Crear Orden de Trabajo</button>
            </form>
        </section>

        <!-- Tabla de órdenes de trabajo pendientes -->
        <section>
            <h2>Órdenes de Trabajo Pendientes</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Status</th>
                    <th>Fecha de Creación</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['description']; ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
