<?php
require 'includes/db.php';
require 'includes/auth.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM work_orders WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: work_orders.php");
exit();
