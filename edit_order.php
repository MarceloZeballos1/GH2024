<?php
require 'includes/db.php';
require 'includes/auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: work_orders.php");
    exit();
}

$order = $conn->query("SELECT * FROM work_orders WHERE id = $id")->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE work_orders SET description = ?, status = ? WHERE id = ?");
    $stmt->execute([$description, $status, $id]);

    header("Location: work_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container mt-4">
        <h1>Editar Orden de Trabajo</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <input type="text" name="description" id="description" class="form-control" value="<?= $order['description'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pendiente" <?= $order['status'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="en proceso" <?= $order['status'] === 'en proceso' ? 'selected' : '' ?>>En Proceso</option>
                    <option value="completado" <?= $order['status'] === 'completado' ? 'selected' : '' ?>>Completado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="work_orders.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
