<?php
require_once '../Config/db.php';

$title = $_POST['title'];
$city = $_POST['city'];
$days = $_POST['days'];
$style = $_POST['style'];
$price = $_POST['price'];
$rating = $_POST['rating'];

$stmt = $pdo->prepare("INSERT INTO packages (title, city, days, style, price, rating) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$title, $city, $days, $style, $price, $rating]);
$packageId = $pdo->lastInsertId();

// Images
foreach ($_POST['images'] as $url) {
  if (trim($url)) {
    $pdo->prepare("INSERT INTO images (package_id, url) VALUES (?, ?)")->execute([$packageId, $url]);
  }
}

// Highlights
foreach ($_POST['highlights'] as $text) {
  if (trim($text)) {
    $pdo->prepare("INSERT INTO highlights (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
  }
}

// Inclusions
for ($i = 0; $i < count($_POST['inclusions_label']); $i++) {
  $label = $_POST['inclusions_label'][$i];
  $icon = $_POST['inclusions_icon'][$i];
  if (trim($label)) {
    $pdo->prepare("INSERT INTO inclusions (package_id, label, icon) VALUES (?, ?, ?)")->execute([$packageId, $label, $icon]);
  }
}

// Exclusions
foreach ($_POST['exclusions'] as $text) {
  if (trim($text)) {
    $pdo->prepare("INSERT INTO exclusions (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
  }
}

// Where to Visit
for ($i = 0; $i < count($_POST['visit_place']); $i++) {
  $place = $_POST['visit_place'][$i];
  $image = $_POST['visit_image'][$i];
  $desc = $_POST['visit_desc'][$i];
  if (trim($place)) {
    $pdo->prepare("INSERT INTO where_to_visit (package_id, place, image, description) VALUES (?, ?, ?, ?)")
      ->execute([$packageId, $place, $image, $desc]);
  }
}

// Activities
for ($i = 0; $i < count($_POST['activity_name']); $i++) {
  $name = $_POST['activity_name'][$i];
  $image = $_POST['activity_image'][$i];
  if (trim($name)) {
    $pdo->prepare("INSERT INTO activities (package_id, name, image) VALUES (?, ?, ?)")
      ->execute([$packageId, $name, $image]);
  }
}

// Itinerary
for ($i = 0; $i < count($_POST['day']); $i++) {
  $day = $_POST['day'][$i];
  $title = $_POST['day_title'][$i];
  $desc = $_POST['day_desc'][$i];
  if (trim($day)) {
    $pdo->prepare("INSERT INTO itinerary (package_id, day, title, description) VALUES (?, ?, ?, ?)")
      ->execute([$packageId, $day, $title, $desc]);
  }
}

header("Location: dashboard.php");
exit;
