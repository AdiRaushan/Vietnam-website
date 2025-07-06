<?php
require_once '../Config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $city = $_POST['city'];
    $days = $_POST['days'];
    $style = $_POST['style'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];

    $stmt = $pdo->prepare("INSERT INTO packages (title, city, days, style, price, rating) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $city, $days, $style, $price, $rating]);
    $packageId = $pdo->lastInsertId();

    foreach ($_POST['images'] as $url) {
        if (!empty($url)) {
            $pdo->prepare("INSERT INTO images (package_id, url) VALUES (?, ?)")->execute([$packageId, $url]);
        }
    }

    foreach ($_POST['highlights'] as $text) {
        if (!empty($text)) {
            $pdo->prepare("INSERT INTO highlights (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
        }
    }

    foreach ($_POST['inclusion_labels'] as $i => $label) {
        $icon = $_POST['inclusion_icons'][$i] ?? '';
        if (!empty($label)) {
            $pdo->prepare("INSERT INTO inclusions (package_id, label, icon) VALUES (?, ?, ?)")->execute([$packageId, $label, $icon]);
        }
    }

    foreach ($_POST['exclusions'] as $text) {
        if (!empty($text)) {
            $pdo->prepare("INSERT INTO exclusions (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
        }
    }

    foreach ($_POST['place'] as $i => $place) {
        $image = $_POST['place_image'][$i] ?? '';
        $desc = $_POST['place_desc'][$i] ?? '';
        if (!empty($place)) {
            $pdo->prepare("INSERT INTO where_to_visit (package_id, place, image, description) VALUES (?, ?, ?, ?)")
                ->execute([$packageId, $place, $image, $desc]);
        }
    }

    foreach ($_POST['activity_name'] as $i => $name) {
        $image = $_POST['activity_image'][$i] ?? '';
        if (!empty($name)) {
            $pdo->prepare("INSERT INTO activities (package_id, name, image) VALUES (?, ?, ?)")
                ->execute([$packageId, $name, $image]);
        }
    }

    foreach ($_POST['day'] as $i => $day) {
        $title = $_POST['itinerary_title'][$i] ?? '';
        $desc = $_POST['itinerary_desc'][$i] ?? '';
        if (!empty($day)) {
            $pdo->prepare("INSERT INTO itinerary (package_id, day, title, description) VALUES (?, ?, ?, ?)")
                ->execute([$packageId, $day, $title, $desc]);
        }
    }

    header("Location: dashboard.php");
    exit;
}
