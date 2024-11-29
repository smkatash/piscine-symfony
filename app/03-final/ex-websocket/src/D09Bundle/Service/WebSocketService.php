<?php

namespace App\D09Bundle\Service;

use Ratchet\Client\WebSocket;

class WebSocketService
{
    private $webSocketClient;

    public function __construct()
    {
        $this->webSocketClient = new WebSocket('ws://localhost:8080');
    }

    public function sendNewPostToClients($post)
    {
        $message = json_encode([
            'type' => 'newPost',
            'data' => ['post' => $post]
        ]);

        $this->webSocketClient->send($message);
    }

    public function sendDeletePostToClients($postId)
    {
        $message = json_encode([
            'type' => 'deletePost',
            'data' => ['postId' => $postId]
        ]);

        $this->webSocketClient->send($message);
    }
}

?>