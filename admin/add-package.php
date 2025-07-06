<?php require_once '../Config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Package</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
  <script>
    function addField(sectionId, templateId) {
      const container = document.getElementById(sectionId);
      const template = document.getElementById(templateId).innerHTML;
      container.insertAdjacentHTML('beforeend', template);
    }
  </script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-6">Add New Package</h1>
  <form action="save-package.php" method="post">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <input name="title" required placeholder="Title" class="p-3 border rounded">
      <input name="city" required placeholder="City" class="p-3 border rounded">
      <input name="days" type="number" required placeholder="Days" class="p-3 border rounded">
      <input name="style" required placeholder="Style" class="p-3 border rounded">
      <input name="price" type="number" required placeholder="Price" class="p-3 border rounded">
      <input name="rating" type="number" step="0.1" required placeholder="Rating" class="p-3 border rounded">
    </div>

    <!-- Images -->
    <h2 class="text-xl font-bold mt-8 mb-2">Images</h2>
    <div id="imagesSection" class="space-y-2"></div>
    <button type="button" onclick="addField('imagesSection', 'imgTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Image</button>

    <!-- Highlights -->
    <h2 class="text-xl font-bold mt-8 mb-2">Highlights</h2>
    <div id="highlightsSection" class="space-y-2"></div>
    <button type="button" onclick="addField('highlightsSection', 'highlightTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Highlight</button>

    <!-- Inclusions -->
    <h2 class="text-xl font-bold mt-8 mb-2">Inclusions</h2>
    <div id="inclusionsSection" class="space-y-2"></div>
    <button type="button" onclick="addField('inclusionsSection', 'inclusionTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Inclusion</button>

    <!-- Exclusions -->
    <h2 class="text-xl font-bold mt-8 mb-2">Exclusions</h2>
    <div id="exclusionsSection" class="space-y-2"></div>
    <button type="button" onclick="addField('exclusionsSection', 'exclusionTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Exclusion</button>

    <!-- Where To Visit -->
    <h2 class="text-xl font-bold mt-8 mb-2">Where to Visit</h2>
    <div id="visitSection" class="space-y-2"></div>
    <button type="button" onclick="addField('visitSection', 'visitTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Place</button>

    <!-- Activities -->
    <h2 class="text-xl font-bold mt-8 mb-2">Activities</h2>
    <div id="activitiesSection" class="space-y-2"></div>
    <button type="button" onclick="addField('activitiesSection', 'activityTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Activity</button>

    <!-- Itinerary -->
    <h2 class="text-xl font-bold mt-8 mb-2">Itinerary</h2>
    <div id="itinerarySection" class="space-y-2"></div>
    <button type="button" onclick="addField('itinerarySection', 'itineraryTpl')" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Day</button>

    <button type="submit" class="block mt-10 bg-green-600 text-white px-6 py-3 rounded">Save Package</button>
  </form>

  <!-- Templates -->
  <template id="imgTpl"><input name="images[]" placeholder="Image URL" class="p-3 border rounded w-full"></template>
  <template id="highlightTpl"><input name="highlights[]" placeholder="Highlight" class="p-3 border rounded w-full"></template>
  <template id="inclusionTpl">
    <div class="flex gap-2">
      <input name="inclusions_label[]" placeholder="Label" class="p-3 border rounded w-full">
      <input name="inclusions_icon[]" placeholder="Icon class" class="p-3 border rounded w-full">
    </div>
  </template>
  <template id="exclusionTpl"><input name="exclusions[]" placeholder="Exclusion" class="p-3 border rounded w-full"></template>
  <template id="visitTpl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input name="visit_place[]" placeholder="Place name" class="p-3 border rounded">
      <input name="visit_image[]" placeholder="Image URL" class="p-3 border rounded">
      <input name="visit_desc[]" placeholder="Description" class="p-3 border rounded">
    </div>
  </template>
  <template id="activityTpl">
    <div class="flex gap-2">
      <input name="activity_name[]" placeholder="Activity name" class="p-3 border rounded w-full">
      <input name="activity_image[]" placeholder="Image URL" class="p-3 border rounded w-full">
    </div>
  </template>
  <template id="itineraryTpl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input name="day[]" placeholder="Day" class="p-3 border rounded">
      <input name="day_title[]" placeholder="Title" class="p-3 border rounded">
      <input name="day_desc[]" placeholder="Description" class="p-3 border rounded">
    </div>
  </template>
</body>
</html>