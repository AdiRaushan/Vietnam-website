<?php
require 'db.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, POST"); // Allow flexibility for older clients

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Package ID is required']);
    exit;
}

try {
    // Delete from main table (relational data auto-deleted via ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Package not found or already deleted']);
    } else {
        echo json_encode(['success' => true, 'message' => "Package $id deleted"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Deletion failed', 'details' => $e->getMessage()]);
}
