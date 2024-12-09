<?php
require 'includes/db.php';
require 'includes/auth.php';

// Inicializar variables de filtro
$search = $_GET['search'] ?? '';

// Consulta con filtro
$sql = "SELECT * FROM equipments";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE id_equipo LIKE :search OR name LIKE :search";
    $params['search'] = "%$search%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$equipments = $stmt->fetchAll();
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

        <!-- Formulario de Búsqueda Mejorado -->
<form class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="input-group shadow-sm">
                <input 
                    type="text" 
                    id="search" 
                    class="form-control form-control-lg border-primary" 
                    placeholder="🔍 Buscar por ID o Nombre" 
                    aria-label="Buscar"
                >
            </div>
        </div>
    </div>
</form>



        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID del Equipo</th>
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
                    <th>Proveedor de Mantenimiento</th>
                    <th>Proveedor de Compra</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($equipments) > 0): ?>
                    <?php foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipment['id_equipo']) ?></td>
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
                <?php else: ?>
                    <tr>
                        <td colspan="15" class="text-center">No se encontraron resultados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'includes/footer.php'; ?>

    <script>
        document.getElementById('search').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const idEquipo = row.cells[0].textContent.toLowerCase();
                const nombre = row.cells[1].textContent.toLowerCase();

                if (idEquipo.includes(query) || nombre.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
