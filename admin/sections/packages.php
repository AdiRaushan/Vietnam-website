<?php
require_once __DIR__ . '/../../Config/db.php';

$stmt = $pdo->query("SELECT id, title, city, days, price FROM packages ORDER BY id DESC");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="min-h-screen flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg p-6 hidden md:block">
      <h2 class="text-xl font-bold mb-6">📋 Admin Panel</h2>
      <nav class="space-y-2">
        <a href="dashboard.php" class="block px-3 py-2 rounded bg-blue-600 text-white">🏠 Package Management</a>
        <a href="add-package.php" class="block px-3 py-2 rounded hover:bg-blue-100">➕ Add Package</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">All Travel Packages</h1>
        <a href="add-package.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">+ Add Package</a>
      </div>

      <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Title</th>
              <th class="px-6 py-3">City</th>
              <th class="px-6 py-3">Days</th>
              <th class="px-6 py-3">Price</th>
              <th class="px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($packages as $p): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4"><?= $p['id'] ?></td>
                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($p['title']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($p['city']) ?></td>
                <td class="px-6 py-4"><?= $p['days'] ?> days</td>
                <td class="px-6 py-4">₹<?= number_format($p['price']) ?></td>
                <td class="px-6 py-4 space-x-2">
                  <a href="edit-package.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                  <a href="delete-package.php?id=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this package?')" class="text-red-600 hover:underline">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($packages)): ?>
              <tr>
                <td colspan="6" class="text-center px-6 py-4 text-gray-500">No packages found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

</body>
</html>
