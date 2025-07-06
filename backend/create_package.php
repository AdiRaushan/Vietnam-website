<?php
require 'db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Validate required fields
$required = ['title', 'city', 'style', 'days', 'price', 'rating'];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        http_response_code(400);
        echo json_encode(['error' => "Missing field: $field"]);
        exit;
    }
}

try {
    // Insert into main package table
    $stmt = $pdo->prepare("INSERT INTO packages (id, title, city, style, days, price, rating) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],
        $_POST['city'],
        $_POST['style'],
        $_POST['days'],
        $_POST['price'],
        $_POST['rating']
    ]);
    $packageId = $pdo->lastInsertId();

    // Helpers
    function insertMultiple($pdo, $table, $fields, $items, $packageId) {
        $stmt = $pdo->prepare("INSERT INTO $table (package_id, " . implode(', ', $fields) . ") VALUES (" . implode(',', array_fill(0, count($fields) + 1, '?')) . ")");
        foreach ($items as $item) {
            $values = [$packageId];
            foreach ($fields as $field) {
                $values[] = $item[$field] ?? '';
            }
            $stmt->execute($values);
        }
    }

    // Optional relational inserts
    if (!empty($_POST['images'])) {
        $images = [];
        foreach ($_POST['images'] as $url) {
            $images[] = ['url' => $url];
        }
        insertMultiple($pdo, 'images', ['url'], $images, $packageId);
    }

    if (!empty($_POST['highlights'])) {
        $highlights = [];
        foreach ($_POST['highlights'] as $text) {
            $highlights[] = ['text' => $text];
        }
        insertMultiple($pdo, 'highlights', ['text'], $highlights, $packageId);
    }

    if (!empty($_POST['exclusions'])) {
        $exclusions = [];
        foreach ($_POST['exclusions'] as $text) {
            $exclusions[] = ['text' => $text];
        }
        insertMultiple($pdo, 'exclusions', ['text'], $exclusions, $packageId);
    }

    if (!empty($_POST['inclusions'])) {
        $inclusions = [];
        foreach ($_POST['inclusions'] as $i => $val) {
            $inclusions[] = [
                'label' => $_POST['inclusions'][$i]['label'] ?? '',
                'icon'  => $_POST['inclusions'][$i]['icon'] ?? ''
            ];
        }
        insertMultiple($pdo, 'inclusions', ['label', 'icon'], $inclusions, $packageId);
    }

    if (!empty($_POST['activities'])) {
        $activities = [];
        foreach ($_POST['activities'] as $i => $val) {
            $activities[] = [
                'name' => $_POST['activities'][$i]['name'] ?? '',
                'image' => $_POST['activities'][$i]['image'] ?? ''
            ];
        }
        insertMultiple($pdo, 'activities', ['name', 'image'], $activities, $packageId);
    }

    if (!empty($_POST['where_to_visit'])) {
        $visits = [];
        foreach ($_POST['where_to_visit'] as $i => $val) {
            $visits[] = [
                'place' => $_POST['where_to_visit'][$i]['place'] ?? '',
                'image' => $_POST['where_to_visit'][$i]['image'] ?? '',
                'description' => $_POST['where_to_visit'][$i]['description'] ?? ''
            ];
        }
        insertMultiple($pdo, 'where_to_visit', ['place', 'image', 'description'], $visits, $packageId);
    }

    if (!empty($_POST['itinerary'])) {
        $itinerary = [];
        foreach ($_POST['itinerary'] as $i => $val) {
            $itinerary[] = [
                'day' => $_POST['itinerary'][$i]['day'] ?? '',
                'title' => $_POST['itinerary'][$i]['title'] ?? '',
                'description' => $_POST['itinerary'][$i]['description'] ?? ''
            ];
        }
        insertMultiple($pdo, 'itinerary', ['day', 'title', 'description'], $itinerary, $packageId);
    }

    echo json_encode(['success' => true, 'package_id' => $packageId]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
