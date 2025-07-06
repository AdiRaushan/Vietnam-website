<?php
require_once '../Config/db.php';

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit;