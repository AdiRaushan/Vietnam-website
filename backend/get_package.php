<?php
require 'db.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    // 🔍 Fetch single package with full details
    $result = $conn->query("SELECT * FROM packages WHERE id = $id");
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Package not found']);
        exit;
    }

    $pkg = $result->fetch_assoc();

    // Fetch relational data
    $relations = [
        'images' => 'url',
        'highlights' => 'text',
        'exclusions' => 'text',
        'inclusions' => ['label', 'icon'],
        'activities' => ['name', 'image'],
        'where_to_visit' => ['place', 'image', 'description'],
        'itinerary' => ['day', 'title', 'description']
    ];

    foreach ($relations as $table => $columns) {
        $res = $conn->query("SELECT * FROM $table WHERE package_id = $id");
        if (is_array($columns)) {
            $pkg[$table] = [];
            while ($row = $res->fetch_assoc()) {
                $pkg[$table][] = $row;
            }
        } else {
            // Simple array (like image URLs or highlights)
            $pkg[$table] = [];
            while ($row = $res->fetch_assoc()) {
                $pkg[$table][] = $row[$columns];
            }
        }
    }

    echo json_encode($pkg);

} else {
    // 📦 Fetch all packages (basic info)
    $result = $conn->query("SELECT * FROM packages ORDER BY id DESC");

    $packages = [];
    while ($row = $result->fetch_assoc()) {
        // Get first image
        $imgRes = $conn->query("SELECT url FROM images WHERE package_id = {$row['id']} LIMIT 1");
        $row['images'] = $imgRes->num_rows ? [$imgRes->fetch_assoc()['url']] : [];

        $packages[] = $row;
    }

    echo json_encode($packages);
}
