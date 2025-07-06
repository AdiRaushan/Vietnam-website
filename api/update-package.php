<?php
require_once '../Config/db.php';

$id = $_POST['id'] ?? null;
if (!$id) {
  die("Invalid ID");
}

// Update main package info
$pdo->prepare("UPDATE packages SET title=?, city=?, days=?, style=?, price=?, rating=? WHERE id=?")
    ->execute([
      $_POST['title'] ?? '',
      $_POST['city'] ?? '',
      $_POST['days'] ?? 0,
      $_POST['style'] ?? '',
      $_POST['price'] ?? 0,
      $_POST['rating'] ?? 0,
      $id
    ]);

// ------------------ DELETE REQUESTED IDS ------------------
$deleteMappings = [
  'delete_image_ids' => 'images',
  'delete_highlight_ids' => 'highlights',
  'delete_inclusion_ids' => 'inclusions',
  'delete_exclusion_ids' => 'exclusions',
  'delete_visit_ids' => 'where_to_visit',
  'delete_activity_ids' => 'activities',
  'delete_itinerary_ids' => 'itinerary',
];

foreach ($deleteMappings as $key => $table) {
  if (!empty($_POST[$key])) {
    foreach ($_POST[$key] as $deleteId) {
      $pdo->prepare("DELETE FROM $table WHERE id = ?")->execute([$deleteId]);
    }
  }
}

// ------------------ IMAGES ------------------
foreach ($_POST['images_existing'] ?? [] as $imageId => $url) {
  $pdo->prepare("UPDATE images SET url = ? WHERE id = ? AND package_id = ?")->execute([$url, $imageId, $id]);
}
foreach ($_POST['images'] ?? [] as $url) {
  if (trim($url) !== '') {
    $pdo->prepare("INSERT INTO images (package_id, url) VALUES (?, ?)")->execute([$id, $url]);
  }
}

// ------------------ HIGHLIGHTS ------------------
foreach ($_POST['highlights_existing'] ?? [] as $highlightId => $text) {
  $pdo->prepare("UPDATE highlights SET text = ? WHERE id = ? AND package_id = ?")->execute([$text, $highlightId, $id]);
}
foreach ($_POST['highlights'] ?? [] as $text) {
  if (trim($text) !== '') {
    $pdo->prepare("INSERT INTO highlights (package_id, text) VALUES (?, ?)")->execute([$id, $text]);
  }
}

// ------------------ INCLUSIONS ------------------
foreach ($_POST['inclusions_existing_labels'] ?? [] as $inclusionId => $label) {
  $icon = $_POST['inclusions_existing_icons'][$inclusionId] ?? '';
  $pdo->prepare("UPDATE inclusions SET label = ?, icon = ? WHERE id = ? AND package_id = ?")
      ->execute([$label, $icon, $inclusionId, $id]);
}
for ($i = 0; $i < count($_POST['inclusions_labels'] ?? []); $i++) {
  $label = $_POST['inclusions_labels'][$i];
  $icon = $_POST['inclusions_icons'][$i];
  if (trim($label) !== '') {
    $pdo->prepare("INSERT INTO inclusions (package_id, label, icon) VALUES (?, ?, ?)")->execute([$id, $label, $icon]);
  }
}

// ------------------ EXCLUSIONS ------------------
foreach ($_POST['exclusions_existing'] ?? [] as $exclusionId => $text) {
  $pdo->prepare("UPDATE exclusions SET text = ? WHERE id = ? AND package_id = ?")->execute([$text, $exclusionId, $id]);
}
foreach ($_POST['exclusions'] ?? [] as $text) {
  if (trim($text) !== '') {
    $pdo->prepare("INSERT INTO exclusions (package_id, text) VALUES (?, ?)")->execute([$id, $text]);
  }
}

// ------------------ WHERE TO VISIT ------------------
foreach ($_POST['visit_place_existing'] ?? [] as $visitId => $place) {
  $image = $_POST['visit_image_existing'][$visitId] ?? '';
  $desc = $_POST['visit_desc_existing'][$visitId] ?? '';
  $pdo->prepare("UPDATE where_to_visit SET place = ?, image = ?, description = ? WHERE id = ? AND package_id = ?")
      ->execute([$place, $image, $desc, $visitId, $id]);
}
for ($i = 0; $i < count($_POST['visit_place'] ?? []); $i++) {
  $place = $_POST['visit_place'][$i] ?? '';
  $img = $_POST['visit_image'][$i] ?? '';
  $desc = $_POST['visit_desc'][$i] ?? '';
  if (trim($place) !== '') {
    $pdo->prepare("INSERT INTO where_to_visit (package_id, place, image, description) VALUES (?, ?, ?, ?)")
        ->execute([$id, $place, $img, $desc]);
  }
}

// ------------------ ACTIVITIES ------------------
foreach ($_POST['activity_name_existing'] ?? [] as $actId => $name) {
  $image = $_POST['activity_image_existing'][$actId] ?? '';
  $pdo->prepare("UPDATE activities SET name = ?, image = ? WHERE id = ? AND package_id = ?")
      ->execute([$name, $image, $actId, $id]);
}
for ($i = 0; $i < count($_POST['activity_name'] ?? []); $i++) {
  $name = $_POST['activity_name'][$i] ?? '';
  $img = $_POST['activity_image'][$i] ?? '';
  if (trim($name) !== '') {
    $pdo->prepare("INSERT INTO activities (package_id, name, image) VALUES (?, ?, ?)")
        ->execute([$id, $name, $img]);
  }
}

// ------------------ ITINERARY ------------------
foreach ($_POST['itinerary_day_existing'] ?? [] as $itinId => $day) {
  $title = $_POST['itinerary_title_existing'][$itinId] ?? '';
  $desc = $_POST['itinerary_desc_existing'][$itinId] ?? '';
  $pdo->prepare("UPDATE itinerary SET day = ?, title = ?, description = ? WHERE id = ? AND package_id = ?")
      ->execute([$day, $title, $desc, $itinId, $id]);
}
for ($i = 0; $i < count($_POST['itinerary_day'] ?? []); $i++) {
  $day = $_POST['itinerary_day'][$i] ?? '';
  $title = $_POST['itinerary_title'][$i] ?? '';
  $desc = $_POST['itinerary_desc'][$i] ?? '';
  if (trim($day) !== '') {
    $pdo->prepare("INSERT INTO itinerary (package_id, day, title, description) VALUES (?, ?, ?, ?)")
        ->execute([$id, $day, $title, $desc]);
  }
}

header("Location: ../admin/dashboard.php");
exit;
