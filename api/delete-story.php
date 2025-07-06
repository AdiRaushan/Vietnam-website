<?php
require_once '../Config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = (int) $_POST['id'];

  // Delete image file first
  $stmt = $pdo->prepare("SELECT image FROM travel_stories WHERE id = ?");
  $stmt->execute([$id]);
  $story = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($story && file_exists($_SERVER['DOCUMENT_ROOT'] . $story['image'])) {
    unlink($_SERVER['DOCUMENT_ROOT'] . $story['image']);
  }

  // Delete from DB
  $stmt = $pdo->prepare("DELETE FROM travel_stories WHERE id = ?");
  $stmt->execute([$id]);
}

header('Location: ../admin/dashboard.php?page=travel-stories');
exit;
