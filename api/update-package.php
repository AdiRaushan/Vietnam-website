<?php
require_once '../Config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $packageId = $_POST['id']; // hidden input in form

        // 1. Update the main package info
        $stmt = $pdo->prepare("UPDATE packages SET title = ?, city = ?, days = ?, style = ?, price = ?, rating = ? WHERE id = ?");
        $stmt->execute([
            $_POST['title'],
            $_POST['city'],
            $_POST['days'],
            $_POST['style'],
            $_POST['price'],
            $_POST['rating'],
            $packageId
        ]);

        // 2. Delete all old child data
        $tables = ['images', 'highlights', 'inclusions', 'exclusions', 'where_to_visit', 'activities', 'itinerary'];
        foreach ($tables as $table) {
            $pdo->prepare("DELETE FROM $table WHERE package_id = ?")->execute([$packageId]);
        }

        // 3. Re-insert all new updated child data

        // Images
        if (!empty($_POST['images_existing'])) {
            foreach ($_POST['images_existing'] as $id => $url) {
                if (!empty($url)) {
                    $pdo->prepare("INSERT INTO images (package_id, url) VALUES (?, ?)")->execute([$packageId, $url]);
                }
            }
        }

        // Highlights
        if (!empty($_POST['highlights_text'])) {
            foreach ($_POST['highlights_text'] as $text) {
                if (!empty($text)) {
                    $pdo->prepare("INSERT INTO highlights (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
                }
            }
        }

        // Inclusions
        if (!empty($_POST['inclusions_label']) && is_array($_POST['inclusions_label'])) {
            for ($i = 0; $i < count($_POST['inclusions_label']); $i++) {
                $label = trim($_POST['inclusions_label'][$i]);
                $icon  = trim($_POST['inclusions_icon'][$i]);
                if (!empty($label)) {
                    $pdo->prepare("INSERT INTO inclusions (package_id, label, icon) VALUES (?, ?, ?)")->execute([$packageId, $label, $icon]);
                }
            }
        }

        // Exclusions
        if (!empty($_POST['exclusions_text'])) {
            foreach ($_POST['exclusions_text'] as $text) {
                if (!empty($text)) {
                    $pdo->prepare("INSERT INTO exclusions (package_id, text) VALUES (?, ?)")->execute([$packageId, $text]);
                }
            }
        }

        // Where to Visit
        if (!empty($_POST['visits_place'])) {
            foreach ($_POST['visits_place'] as $i => $place) {
                $image = $_POST['visits_image'][$i] ?? '';
                $desc  = $_POST['visits_description'][$i] ?? '';
                if (!empty($place)) {
                    $pdo->prepare("INSERT INTO where_to_visit (package_id, place, image, description) VALUES (?, ?, ?, ?)")
                        ->execute([$packageId, $place, $image, $desc]);
                }
            }
        }

        // Activities
        if (!empty($_POST['activities_name'])) {
            foreach ($_POST['activities_name'] as $i => $name) {
                $image = $_POST['activities_image'][$i] ?? '';
                if (!empty($name)) {
                    $pdo->prepare("INSERT INTO activities (package_id, name, image) VALUES (?, ?, ?)")
                        ->execute([$packageId, $name, $image]);
                }
            }
        }

        // Itinerary
        if (!empty($_POST['itinerary_day'])) {
            foreach ($_POST['itinerary_day'] as $i => $day) {
                $title = $_POST['itinerary_title'][$i] ?? '';
                $desc  = $_POST['itinerary_description'][$i] ?? '';
                if (!empty($day)) {
                    $pdo->prepare("INSERT INTO itinerary (package_id, day, title, description) VALUES (?, ?, ?, ?)")
                        ->execute([$packageId, $day, $title, $desc]);
                }
            }
        }

        // Done!
        $pdo->commit();
        header("Location: ../admin/dashboard.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Update failed: " . $e->getMessage());
    }
}
