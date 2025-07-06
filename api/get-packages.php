<?php
require_once '../Config/db.php';

header('Content-Type: application/json');

// Step 1: Get all packages
$packagesStmt = $pdo->query("SELECT * FROM packages ORDER BY id DESC");
$packages = $packagesStmt->fetchAll(PDO::FETCH_ASSOC);

// Step 2: For each package, fetch all nested data
foreach ($packages as &$package) {
    $id = $package['id'];

    // Images
    $stmt = $pdo->prepare("SELECT url FROM images WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['images'] = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'url');

    // Highlights
    $stmt = $pdo->prepare("SELECT text FROM highlights WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['highlights'] = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'text');

    // Inclusions
    $stmt = $pdo->prepare("SELECT label, icon FROM inclusions WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['inclusions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Exclusions
    $stmt = $pdo->prepare("SELECT text FROM exclusions WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['exclusions'] = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'text');

    // Where to visit
    $stmt = $pdo->prepare("SELECT place, image, description FROM where_to_visit WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['where_to_visit'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Activities
    $stmt = $pdo->prepare("SELECT name, image FROM activities WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['activities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Itinerary
    $stmt = $pdo->prepare("SELECT day, title, description FROM itinerary WHERE package_id = ?");
    $stmt->execute([$id]);
    $package['itinerary'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Step 3: Send it as JSON
echo json_encode($packages);
