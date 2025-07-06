<?php
$page = $_GET['page'] ?? 'packages'; // default to packages
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

  <!-- Sidebar -->
  <div class="w-64 bg-white shadow-lg p-6">
    <h2 class="text-xl font-bold mb-6">Admin Dashboard</h2>
    <nav class="space-y-3">
      <a href="?page=packages" class="block text-blue-600 hover:underline">📦 Packages</a>
      <a href="?page=travel-stories" class="block text-blue-600 hover:underline">📖 Travel Stories</a>
      <a href="?page=blogs" class="block text-blue-600 hover:underline">📰 Blogs</a>
      <a href="?page=banners" class="block text-blue-600 hover:underline">🎯 Homepage Banners</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-8 bg-gray-50">
    <?php
      $sectionFile = __DIR__ . "/sections/$page.php";
      if (file_exists($sectionFile)) {
        include $sectionFile;
      } else {
        echo "<p class='text-red-500'>Section not found.</p>";
      }
    ?>
  </div>

</body>
</html>
