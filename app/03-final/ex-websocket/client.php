<?php
// Include the Composer autoloader
require 'vendor/autoload.php';

use WebSocket\Client;

// WebSocket server URL
$wsUrl = "ws://localhost:8080";

try {
    // Create a new WebSocket client
    $client = new Client($wsUrl);

    // Once the connection is established
    echo "Connected to WebSocket server\n";

    $authData = json_encode([
        "type" => "newPost",
        "data" => [
            "post" => "This is a new post"
        ]
    ]);
    $client->send($authData);
    
    while (true) {
        $message = $client->receive();
        echo "Message from server: " . $message . "\n";
    }
} catch (Exception $e) {
    // Handle any connection errors
    echo "Error: " . $e->getMessage() . "\n";
}
?>
