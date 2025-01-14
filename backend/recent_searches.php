<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$recentSearchesFile = __DIR__ . '/recent_searches.json';

// Ensure the file exists
if (!file_exists($recentSearchesFile)) {
    file_put_contents($recentSearchesFile, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save a recent search
    $data = json_decode(file_get_contents('php://input'), true);
    $city = $data['city'] ?? null;

    if ($city) {
        $recentSearches = json_decode(file_get_contents($recentSearchesFile), true) ?? [];

        // Add the city to recent searches, avoiding duplicates
        if (!in_array($city, $recentSearches)) {
            array_unshift($recentSearches, $city); // Add to the beginning
            if (count($recentSearches) > 5) {
                array_pop($recentSearches); // Keep only the last 5 searches
            }
            file_put_contents($recentSearchesFile, json_encode($recentSearches));
        }

        echo json_encode(["status" => "success", "recentSearches" => $recentSearches]);
    } else {
        echo json_encode(["status" => "error", "message" => "City not provided"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve recent searches
    $recentSearches = json_decode(file_get_contents($recentSearchesFile), true) ?? [];
    echo json_encode($recentSearches);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
