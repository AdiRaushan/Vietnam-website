<?php
require_once '../Config/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$id]);
$package = $stmt->fetch();

if (!$package) {
  echo "Package not found.";
  exit;
}

function getData($pdo, $table, $id) {
  $stmt = $pdo->prepare("SELECT * FROM $table WHERE package_id = ?");
  $stmt->execute([$id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$images = getData($pdo, 'images', $id);
$highlights = getData($pdo, 'highlights', $id);
$inclusions = getData($pdo, 'inclusions', $id);
$exclusions = getData($pdo, 'exclusions', $id);
$visits = getData($pdo, 'where_to_visit', $id);
$activities = getData($pdo, 'activities', $id);
$itinerary = getData($pdo, 'itinerary', $id);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Package</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
  <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Package: <?= htmlspecialchars($package['title']) ?></h1>

    <form method="POST" action="../api/update-package.php" class="space-y-6">
      <input type="hidden" name="id" value="<?= $package['id'] ?>">

      <!-- BASIC -->
      <fieldset class="space-y-4">
        <legend class="text-xl font-semibold mb-2">Basic Info</legend>
        <input name="title" value="<?= $package['title'] ?>" required placeholder="Package Title" class="w-full p-3 border rounded">
        <input name="city" value="<?= $package['city'] ?>" required placeholder="City" class="w-full p-3 border rounded">
        <input name="days" type="number" value="<?= $package['days'] ?>" required placeholder="Days" class="w-full p-3 border rounded">
        <input name="style" value="<?= $package['style'] ?>" required placeholder="Style (e.g., Adventure, Romantic)" class="w-full p-3 border rounded">
        <input name="price" type="number" value="<?= $package['price'] ?>" required placeholder="Price (in ₹)" class="w-full p-3 border rounded">
        <input name="rating" step="0.1" value="<?= $package['rating'] ?>" required placeholder="Rating (e.g., 4.5)" class="w-full p-3 border rounded">
      </fieldset>

      <!-- DYNAMIC SECTIONS -->
      <?php
      function renderSection($title, $name, $fields, $data) {
        echo "<fieldset class='border-t pt-6'><legend class='text-lg font-semibold mb-2'>$title</legend><div id='{$name}Container' class='space-y-4'>";
        foreach ($data as $index => $item) {
          echo "<div class='item bg-gray-50 p-4 rounded border space-y-2'>";
          foreach ($fields as $fieldName => $placeholder) {
            $val = htmlspecialchars($item[$fieldName] ?? '');
            if ($name === 'images' && $fieldName === 'url' && isset($item['id'])) {
              echo "<input name='images_existing[{$item['id']}]' value='$val' placeholder='$placeholder' class='w-full p-2 border rounded'>";
            } else {
              echo strpos($fieldName, 'desc') !== false || strpos($fieldName, 'description') !== false
                ? "<textarea name='{$name}_{$fieldName}[]' placeholder='$placeholder' class='w-full p-2 border rounded'>$val</textarea>"
                : "<input name='{$name}_{$fieldName}[]' value='$val' placeholder='$placeholder' class='w-full p-2 border rounded'>";
            }
          }
          echo "<button type='button' class='remove-btn bg-red-500 text-white px-3 py-1 rounded' onclick='this.parentNode.remove()'>Remove</button></div>";
        }
        echo "</div><button type='button' class='add-btn mt-3 bg-blue-600 text-white px-4 py-2 rounded' onclick='addSection(\"$name\", " . json_encode($fields) . ")'>➕ Add $title</button></fieldset>";
      }

      renderSection("Images", "images", ["url" => "Image URL"], $images);
      renderSection("Highlights", "highlights", ["text" => "Highlight"], $highlights);
      renderSection("Inclusions", "inclusions", ["label" => "Inclusion Label", "icon" => "Font Awesome Icon Class"], $inclusions);
      renderSection("Exclusions", "exclusions", ["text" => "Exclusion"], $exclusions);
      renderSection("Where to Visit", "visits", [
        "place" => "Place Name",
        "image" => "Image URL",
        "description" => "Short Description"
      ], $visits);
      renderSection("Activities", "activities", ["name" => "Activity Name", "image" => "Image URL"], $activities);
      renderSection("Itinerary", "itinerary", [
        "day" => "Day (e.g., Day 1)",
        "title" => "Title",
        "description" => "Description"
      ], $itinerary);
      ?>

      <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded">💾 Update Package</button>
    </form>

    <div class="mt-6 text-center">
      <a href="dashboard.php" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>
  </div>

  <script>
    function addSection(name, fields) {
      const container = document.getElementById(name + 'Container');
      const wrapper = document.createElement('div');
      wrapper.className = 'item bg-gray-50 p-4 rounded border space-y-2';

      for (const field in fields) {
        const input = document.createElement(field.includes('desc') || field.includes('description') ? 'textarea' : 'input');
        input.name = `${name}_${field}[]`;
        input.placeholder = fields[field];
        input.className = 'w-full p-2 border rounded';
        wrapper.appendChild(input);
      }

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'remove-btn bg-red-500 text-white px-3 py-1 rounded';
      btn.innerText = 'Remove';
      btn.onclick = () => wrapper.remove();

      wrapper.appendChild(btn);
      container.appendChild(wrapper);
    }
  </script>
</body>
</html>