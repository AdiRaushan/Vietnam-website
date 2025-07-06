<?php
require_once '../Config/db.php';

$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$content = $_POST['content'] ?? '';
$imagePath = '';

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = '../uploads/stories/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $filename = time() . '_' . basename($_FILES['image']['name']);
  $targetPath = $uploadDir . $filename;

  if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    // ✅ Save publicly accessible image path
    $imagePath = '/Vietnam/uploads/stories/' . $filename;
  } else {
    die("Image upload failed. Please try again.");
  }
} else {
  die("No image uploaded.");
}

// Insert story into database
$stmt = $pdo->prepare("INSERT INTO travel_stories (title, author, content, image) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $author, $content, $imagePath]);

header('Location: ../admin/dashboard.php?page=travel-stories');
exit;
?>
