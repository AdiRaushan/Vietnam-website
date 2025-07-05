<?php
// Allow CORS if you're testing from another domain (uncomment if needed)
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Content-Type");

// Read the raw POST data
$json = file_get_contents('php://input');

// Validate JSON
if (json_decode($json) === null) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

// Define the path to your JSON file
$file = 'data/data.json';

// Write the JSON to the file
if (file_put_contents($file, $json) !== false) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to write file"]);
}
?>
