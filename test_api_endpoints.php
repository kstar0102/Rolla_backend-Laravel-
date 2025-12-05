<?php
/**
 * API Endpoint Diagnostic Script
 * Run this on your live server to test car-types and droppin endpoints
 * 
 * Usage: php test_api_endpoints.php
 */

$baseUrl = 'http://98.84.126.74/api';

echo "Testing API Endpoints for Live Server\n";
echo "=====================================\n\n";

// Test 1: Car Types Endpoint
echo "1. Testing /api/car_types\n";
echo "   URL: {$baseUrl}/car_types\n";
$ch = curl_init($baseUrl . '/car_types');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

echo "   HTTP Status: {$httpCode}\n";
echo "   Response Body:\n";
$decoded = json_decode($body, true);
if ($decoded) {
    echo "   " . json_encode($decoded, JSON_PRETTY_PRINT) . "\n";
    if (isset($decoded['data']) && is_array($decoded['data'])) {
        echo "   Data Count: " . count($decoded['data']) . "\n";
    }
} else {
    echo "   " . $body . "\n";
}
echo "\n";

// Test 2: Droppin Endpoint
echo "2. Testing /api/droppin/data\n";
echo "   URL: {$baseUrl}/droppin/data\n";
$ch = curl_init($baseUrl . '/droppin/data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

echo "   HTTP Status: {$httpCode}\n";
echo "   Response Body:\n";
$decoded = json_decode($body, true);
if ($decoded) {
    echo "   " . json_encode($decoded, JSON_PRETTY_PRINT) . "\n";
    if (isset($decoded['data']) && is_array($decoded['data'])) {
        echo "   Data Count: " . count($decoded['data']) . "\n";
    }
} else {
    echo "   " . $body . "\n";
}
echo "\n";

echo "=====================================\n";
echo "Diagnostic Complete\n";
echo "\n";
echo "Common Issues:\n";
echo "1. If HTTP Status is 500: Check Laravel logs (storage/logs/laravel.log)\n";
echo "2. If HTTP Status is 404: Check routes are registered correctly\n";
echo "3. If Data Count is 0: Database tables are empty - run seeders\n";
echo "4. If connection fails: Check server is accessible and CORS is configured\n";

