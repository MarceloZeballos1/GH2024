<?php
header('Content-Type: text/html; charset=utf-8');
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/fpdf/fpdf.php'; 

// Obtener la lista de equipos
$equipments = $conn->query("SELECT * FROM equipments")->fetchAll();

// Función para generar el PDF
function generatePDF($equipment) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    // Título
    $pdf->Cell(0, 10, 'Ficha Tecnica del Equipo: ' . $equipment['name'], 0, 1, 'C');
    $pdf->Ln(10);
    
    // Detalles del equipo
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Marca: ' . ($equipment['marca'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Modelo: ' . ($equipment['modelo'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Número de Serie: ' . ($equipment['nro_serie'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Descripción: ' . ($equipment['description'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Estado: ' . $equipment['estado'], 0, 1);
    $pdf->Cell(0, 10, 'Ubicación: ' . ($equipment['location'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Tipo de Equipo: ' . $equipment['tipo_equipo'], 0, 1);
    $pdf->Cell(0, 10, 'Año de Ingreso: ' . ($equipment['gestion_ingreso'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Requisitos de Funcionamiento: ' . ($equipment['requisitos_funcionamiento'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Proveedor de Mantenimiento: ' . ($equipment['proveedor_servicio_mantenimiento'] ?: 'N/A'), 0, 1);
    $pdf->Cell(0, 10, 'Proveedor de Compra: ' . ($equipment['proveedor_compra'] ?: 'N/A'), 0, 1);
    
    // Guardar el PDF en el servidor
    $pdfOutputPath = 'fichas_tecnicas/' . $equipment['id'] . '_ficha_tecnica.pdf';
    $pdf->Output('F', $pdfOutputPath); // Guardar el archivo
    return $pdfOutputPath;
}

// Si se ha enviado un archivo PDF
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['ficha_pdf'])) {
    $equipment_id = $_POST['equipment_id'];
    $file = $_FILES['ficha_pdf'];

    // Verificar que el archivo es un PDF
    if ($file['type'] == 'application/pdf') {
        $upload_dir = 'fichas_tecnicas/';
        $file_path = $upload_dir . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Actualizar la base de datos con la ruta del archivo PDF
            $stmt = $conn->prepare("UPDATE equipments SET ficha_tecnica_pdf = :file_path WHERE id = :id");
            $stmt->execute([':file_path' => $file_path, ':id' => $equipment_id]);
            echo "El archivo PDF se ha cargado correctamente.";
        } else {
            echo "Error al cargar el archivo.";
        }
    } else {
        echo "Solo se permite cargar archivos PDF.";
    }
}

// Si no se ha cargado un PDF, se genera uno nuevo a partir de los datos del equipo
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['equipment_id'])) {
    $equipment_id = $_GET['equipment_id'];
    $equipment = $conn->query("SELECT * FROM equipments WHERE id = $equipment_id")->fetch();
    
    if ($equipment) {
        $pdf_path = generatePDF($equipment);

        // Actualizar la base de datos con la ruta del archivo generado
        $stmt = $conn->prepare("UPDATE equipments SET ficha_tecnica_pdf = :file_path WHERE id = :id");
        $stmt->execute([':file_path' => $pdf_path, ':id' => $equipment_id]);

        echo "La ficha técnica se ha generado correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichas Técnicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h1>Fichas Técnicas de Equipos Médicos</h1>
        
        <!-- Formulario para cargar o generar ficha técnica -->
        <form action="ficha_tecnica.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="equipment_id">Seleccionar Equipo:</label>
                <select name="equipment_id" class="form-control" required>
                    <?php foreach ($equipments as $equipment): ?>
                        <option value="<?= $equipment['id'] ?>"><?= $equipment['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group mt-3">
                <label for="ficha_pdf">Cargar ficha técnica (PDF):</label>
                <input type="file" name="ficha_pdf" class="form-control" accept="application/pdf">
            </div>
            
            <button type="submit" class="btn btn-primary mt-3">Cargar o Generar Ficha Técnica</button>
        </form>

        <h2 class="mt-4">Equipos Existentes y Fichas Técnicas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre del Equipo</th>
                    <th>Ficha Técnica</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= $equipment['name'] ?></td>
                        <td>
                            <?php if ($equipment['ficha_tecnica_pdf']): ?>
                                <a href="<?= $equipment['ficha_tecnica_pdf'] ?>" target="_blank">Ver Ficha Técnica</a>
                            <?php else: ?>
                                <span>No disponible</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

