<?php
require_once '../Config/db.php';

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);
}

echo json_encode(['success' => true]);