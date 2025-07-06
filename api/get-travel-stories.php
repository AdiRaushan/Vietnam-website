<?php
require_once '../Config/db.php';

header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Minimum page 1
$offset = ($page - 1) * $limit;

// Fetch paginated stories
$stmt = $pdo->prepare("SELECT * FROM travel_stories ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();

$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total story count
$total = $pdo->query("SELECT COUNT(*) FROM travel_stories")->fetchColumn();

echo json_encode([
  'stories' => $stories,
  'total' => (int)$total,
  'perPage' => $limit,
  'currentPage' => $page,
  'totalPages' => ceil($total / $limit)
]);
?>
