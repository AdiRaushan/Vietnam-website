<?php
require 'db.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Package ID is required']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Update main package
    $stmt = $pdo->prepare("UPDATE packages SET title=?, city=?, style=?, days=?, price=?, rating=? WHERE id=?");
    $stmt->execute([
        $_POST['title'],
        $_POST['city'],
        $_POST['style'],
        $_POST['days'],
        $_POST['price'],
        $_POST['rating'],
        $id
    ]);

    // Delete old relational data
    $tables = ['images', 'highlights', 'inclusions', 'exclusions', 'where_to_visit', 'activities', 'itinerary'];
    foreach ($tables as $table) {
        $pdo->prepare("DELETE FROM $table WHERE package_id = ?")->execute([$id]);
    }

    // Helper function to fetch array keys like field[0], field[1], ...
    function collectIndexedArray($prefix) {
        $output = [];
        foreach ($_POST as $key => $value) {
            if (preg_match("/^{$prefix}\[(\d+)\]$/", $key, $matches)) {
                $index = $matches[1];
                $output[$index] = $value;
            }
        }
        ksort($output);
        return array_values($output);
    }

    function collectNestedArray($prefix, $keys) {
        $output = [];
        foreach ($_POST as $key => $value) {
            if (preg_match("/^{$prefix}\[(\d+)\]\[([a-z_]+)\]$/", $key, $matches)) {
                $index = $matches[1];
                $field = $matches[2];
                $output[$index][$field] = $value;
            }
        }
        ksort($output);
        return array_values($output);
    }

    // Reinsert new data
    foreach (collectIndexedArray('images') as $url) {
        $pdo->prepare("INSERT INTO images (package_id, url) VALUES (?, ?)")->execute([$id, $url]);
    }

    foreach (collectIndexedArray('highlights') as $text) {
        $pdo->prepare("INSERT INTO highlights (package_id, text) VALUES (?, ?)")->execute([$id, $text]);
    }

    foreach (collectIndexedArray('exclusions') as $text) {
        $pdo->prepare("INSERT INTO exclusions (package_id, text) VALUES (?, ?)")->execute([$id, $text]);
    }

    foreach (collectNestedArray('inclusions', ['label', 'icon']) as $item) {
        $pdo->prepare("INSERT INTO inclusions (package_id, label, icon) VALUES (?, ?, ?)")
             ->execute([$id, $item['label'], $item['icon']]);
    }

    foreach (collectNestedArray('activities', ['name', 'image']) as $item) {
        $pdo->prepare("INSERT INTO activities (package_id, name, image) VALUES (?, ?, ?)")
             ->execute([$id, $item['name'], $item['image']]);
    }

    foreach (collectNestedArray('where_to_visit', ['place', 'image', 'description']) as $item) {
        $pdo->prepare("INSERT INTO where_to_visit (package_id, place, image, description) VALUES (?, ?, ?, ?)")
             ->execute([$id, $item['place'], $item['image'], $item['description']]);
    }

    foreach (collectNestedArray('itinerary', ['day', 'title', 'description']) as $item) {
        $pdo->prepare("INSERT INTO itinerary (package_id, day, title, description) VALUES (?, ?, ?, ?)")
             ->execute([$id, $item['day'], $item['title'], $item['description']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Package updated successfully']);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Update failed', 'details' => $e->getMessage()]);
}
