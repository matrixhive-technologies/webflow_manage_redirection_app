<?php

// Log file path
$logFilePath = __DIR__ . '/webflow_webhook_log.txt';

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Log the request method for debugging
file_put_contents($logFilePath, 'Request Method: ' . $requestMethod . PHP_EOL, FILE_APPEND);

// Allow any HTTP method
if ($requestMethod === 'POST') {
    // Get the raw POST data
    $webhookData = file_get_contents('php://input');

    $data = json_decode(file_get_contents('php://input'), true);

    // Log the raw data for debugging purposes
    file_put_contents($logFilePath, $webhookData . PHP_EOL, FILE_APPEND);

    // Respond to Webflow with a 200 OK status to acknowledge the webhook
    http_response_code(200);
} else {
    // Respond with a 405 Method Not Allowed for any other request method
    http_response_code(405);
}
