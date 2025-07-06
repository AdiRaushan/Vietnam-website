<?php
require_once '../Config/db.php';

header('Content-Type: application/json');
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    echo json_encode(["error" => "Package not found"]);
    exit;
}

$package['images'] = array_column(
    $pdo->query("SELECT url FROM images WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC), 'url');
$package['highlights'] = array_column(
    $pdo->query("SELECT text FROM highlights WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC), 'text');
$package['inclusions'] = $pdo->query("SELECT label, icon FROM inclusions WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC);
$package['exclusions'] = array_column(
    $pdo->query("SELECT text FROM exclusions WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC), 'text');
$package['where_to_visit'] = $pdo->query("SELECT place, image, description FROM where_to_visit WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC);
$package['activities'] = $pdo->query("SELECT name, image FROM activities WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC);
$package['itinerary'] = $pdo->query("SELECT day, title, description FROM itinerary WHERE package_id = $id")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($package);