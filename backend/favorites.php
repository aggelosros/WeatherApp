<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$favoritesFile = __DIR__ . '/favorites.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save a favorite city
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['city'])) {
        $city = $data['city'];
        $favorites = json_decode(file_get_contents($favoritesFile), true) ?? [];

        if (!in_array($city, $favorites)) {
            $favorites[] = $city;
            file_put_contents($favoritesFile, json_encode($favorites));
            echo json_encode(["status" => "success", "favorites" => $favorites]);
        } else {
            echo json_encode(["status" => "exists", "message" => "City already in favorites"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "City not provided"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve favorite cities
    $favorites = json_decode(file_get_contents($favoritesFile), true) ?? [];
    echo json_encode($favorites);
} else {
    echo json_encode(["status" => "error", "message" => "Unsupported HTTP method"]);
}
