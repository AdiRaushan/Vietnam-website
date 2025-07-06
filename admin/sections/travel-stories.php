<?php
require_once __DIR__ . '/../../Config/db.php';

$stmt = $pdo->query("SELECT * FROM travel_stories ORDER BY created_at DESC");
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Add New Story Form -->
<section class="p-6 bg-white rounded shadow max-w-3xl mx-auto mt-6">
  <h2 class="text-2xl font-bold mb-2">📖 Add New Travel Story</h2>
  <p class="text-gray-600 mb-6">Fill in the details below to create a new travel story.</p>

  <form method="POST" action="/Vietnam/api/add-story.php" enctype="multipart/form-data" class="space-y-4">
    <!-- Title -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Title</label>
      <input type="text" name="title" placeholder="Story Title"
             class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <!-- Author -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Author</label>
      <input type="text" name="author" placeholder="Author Name"
             class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <!-- Content -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Content</label>
      <textarea name="content" rows="5" placeholder="Story content here..."
                class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-400" required></textarea>
    </div>

    <!-- Image Upload -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Image</label>
      <input type="file" name="image" accept="image/*"
             class="w-full mt-1 text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
             file:rounded-full file:border-0
             file:text-sm file:font-semibold
             file:bg-blue-50 file:text-blue-700
             hover:file:bg-blue-100" required>
    </div>

    <!-- Submit -->
    <div>
      <button type="submit"
              class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow">➕ Add Story</button>
    </div>
  </form>
</section>

<!-- Show Existing Stories -->
<section class="max-w-7xl mx-auto px-4 mt-10">
  <h2 class="text-2xl font-bold mb-6">📝 All Travel Stories</h2>

  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($stories as $story): ?>
      <div class="bg-white rounded-lg shadow p-4 flex flex-col">
        <img
          src="<?= !empty($story['image']) ? htmlspecialchars($story['image']) : '/Vietnam/assets/placeholder.jpg' ?>"
          alt="<?= htmlspecialchars($story['title']) ?>"
          class="h-48 w-full object-cover rounded mb-3 border"
          onerror="this.onerror=null;this.src='/Vietnam/assets/placeholder.jpg';"
        />

        <h3 class="text-lg font-bold"><?= htmlspecialchars($story['title']) ?></h3>
        <p class="text-sm text-gray-600">By <?= htmlspecialchars($story['author'] ?? 'Anonymous') ?></p>
        <p class="text-sm text-gray-700 mt-1">
          <?= htmlspecialchars(mb_strimwidth($story['content'], 0, 100, '...')) ?>
        </p>

        <form method="POST" action="/Vietnam/api/delete-story.php" class="mt-3">
          <input type="hidden" name="id" value="<?= $story['id'] ?>">
          <button type="submit"
                  onclick="return confirm('Are you sure you want to delete this story?')"
                  class="text-red-600 text-sm hover:underline">
            🗑️ Delete
          </button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (empty($stories)): ?>
    <p class="text-center text-gray-500 mt-10">No travel stories added yet.</p>
  <?php endif; ?>
</section>

