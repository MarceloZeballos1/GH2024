<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'includes/db.php';

if (isset($_POST['create_order'])) {
    $user_id = $_SESSION['user_id'];
    $equipment_id = $_POST['equipment_id'];
    $description = $_POST['description'];

    // Insertar nueva orden de trabajo
    $stmt = $conn->prepare("INSERT INTO work_orders (user_id, equipment_id, description, status) VALUES (:user_id, :equipment_id, :description, 'pending')");
    $stmt->execute(['user_id' => $user_id, 'equipment_id' => $equipment_id, 'description' => $description]);

    // Redirigir al panel de usuario
    header("Location: user.php");
    exit();
}
?>
