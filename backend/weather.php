<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the `.env` file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the API key
$apiKey = $_ENV['API_KEY'];
if (!$apiKey) {
    die('Error: API key is missing. Please set it in the .env file.');
}

// Ensure the cache directory exists
$cacheDir = __DIR__ . '/cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

// Log file location
$logFile = __DIR__ . '/access.log';

// Logging function
function logMessage($message, $logFile)
{
    $timeStamp = date("Y-m-d H:i:s");
    $logEntry = "[{$timeStamp}] {$message}" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Check if a city is provided
if (isset($_GET['city'])) {
    $city = urlencode($_GET['city']);
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}";
    $cacheFile = "{$cacheDir}/" . md5($city) . ".json";

    // Check if the cache file exists and is still valid (10 minutes)
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 600) {
        // Serve data from the cache
        logMessage("Cache hit for city: {$city}", $logFile);
        echo file_get_contents($cacheFile);
    } else {

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true); // Include headers in the output

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP response code
        curl_close($ch);

        // Separate the headers and body from the response
        $headerSize = strpos($response, "\r\n\r\n") + 4;
        $body = substr($response, $headerSize);

        if ($httpCode === 404) {
            echo json_encode(["cod" => "404", "message" => "City not found"]);
        } elseif ($httpCode === 200) {
            echo $body; // Output the weather data
        } else {
            echo json_encode(["cod" => $httpCode, "message" => "An error occurred while fetching the weather data"]);
        }
    }
} else {
    logMessage("No city provided in the request.", $logFile);
    echo json_encode(["error" => "City not provided"]);
}
