<?php
require 'db.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try {
    $result = $conn->query("SELECT * FROM packages ORDER BY id DESC");

    $packages = [];

    while ($row = $result->fetch_assoc()) {
        // Fetch first image for thumbnail
        $imgRes = $conn->query("SELECT url FROM images WHERE package_id = {$row['id']} LIMIT 1");
        $row['images'] = $imgRes->num_rows ? [$imgRes->fetch_assoc()['url']] : [];

        $packages[] = $row;
    }

    echo json_encode($packages);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch packages', 'details' => $e->getMessage()]);
}
