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
  <style>
    input, textarea { display: block; margin: 8px 0; width: 100%; }
    fieldset { margin-bottom: 30px; padding: 15px; border: 1px solid #ccc; }
    button.remove-btn { margin: 5px 0; background: #e74c3c; color: #fff; border: none; padding: 4px 10px; cursor: pointer; }
  </style>
</head>
<body>
  <h1>Edit Package: <?= htmlspecialchars($package['title']) ?></h1>

  <form method="POST" action="../api/update-package.php">
    <input type="hidden" name="id" value="<?= $package['id'] ?>">

    <!-- BASIC -->
    <fieldset><legend>Basic Info</legend>
      <input name="title" value="<?= $package['title'] ?>" required>
      <input name="city" value="<?= $package['city'] ?>" required>
      <input name="days" type="number" value="<?= $package['days'] ?>" required>
      <input name="style" value="<?= $package['style'] ?>" required>
      <input name="price" type="number" value="<?= $package['price'] ?>" required>
      <input name="rating" step="0.1" value="<?= $package['rating'] ?>" required>
    </fieldset>

    <!-- DYNAMIC SECTIONS -->
    <?php
    function renderSection($title, $name, $fields, $data) {
      echo "<fieldset><legend>$title</legend><div id='{$name}Container'>";
      foreach ($data as $index => $item) {
        echo "<div class='item'>";
        foreach ($fields as $fieldName => $placeholder) {
          $val = htmlspecialchars($item[$fieldName] ?? '');
          echo strpos($fieldName, 'desc') !== false || strpos($fieldName, 'description') !== false
              ? "<textarea name='{$name}_{$fieldName}[]' placeholder='$placeholder'>$val</textarea>"
              : "<input name='{$name}_{$fieldName}[]' value='$val' placeholder='$placeholder'>";
        }
        echo "<button type='button' class='remove-btn' onclick='this.parentNode.remove()'>Remove</button></div>";
      }
      echo "</div><button type='button' onclick='addSection(\"$name\", " . json_encode($fields) . ")'>➕ Add $title</button></fieldset>";
    }

    renderSection("Images", "images", ["url" => "Image URL"], $images);
    renderSection("Highlights", "highlights", ["text" => "Highlight"], $highlights);
    renderSection("Inclusions", "inclusions", ["label" => "Label", "icon" => "Icon"], $inclusions);
    renderSection("Exclusions", "exclusions", ["text" => "Exclusion"], $exclusions);
    renderSection("Where to Visit", "visits", [
      "place" => "Place",
      "image" => "Image URL",
      "description" => "Description"
    ], $visits);
    renderSection("Activities", "activities", ["name" => "Activity", "image" => "Image URL"], $activities);
    renderSection("Itinerary", "itinerary", [
      "day" => "Day (e.g., Day 1)",
      "title" => "Title",
      "description" => "Description"
    ], $itinerary);
    ?>

    <button type="submit">💾 Update Package</button>
  </form>

  <a href="dashboard.php">← Back to Dashboard</a>

  <script>
    function addSection(name, fields) {
      const container = document.getElementById(name + 'Container');
      const wrapper = document.createElement('div');
      wrapper.className = 'item';

      for (const field in fields) {
        if (field.includes('desc') || field.includes('description')) {
          const textarea = document.createElement('textarea');
          textarea.name = `${name}_${field}[]`;
          textarea.placeholder = fields[field];
          wrapper.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.name = `${name}_${field}[]`;
          input.placeholder = fields[field];
          wrapper.appendChild(input);
        }
      }

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'remove-btn';
      btn.innerText = 'Remove';
      btn.onclick = () => wrapper.remove();

      wrapper.appendChild(btn);
      container.appendChild(wrapper);
    }
  </script>
</body>
</html>
