<?php

namespace App\D09Bundle\WebsocketHandler;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class PostHandler implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

       if ($data && isset($data['type'])) {
            if ($data['type'] === 'newPost') {
                print_r($data);
                foreach ($this->clients as $client) { {
                        $client->send(json_encode([
                            'type' => 'newPost',
                            'post' => $data['data']['post'] ?? 'No content'
                        ]));
                    }
                }
            } elseif ($data['type'] === 'deletePost') {
                foreach ($this->clients as $client) {{
                        $client->send(json_encode([
                            'type' => 'deletePost',
                            'postId' => $data['data']['postId'] ?? 'No ID'
                        ]));
                    }
                }
            }
        } else {
            echo "No type in the message.";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: " . $e->getMessage() . "\n";
        $this->clients->detach($conn);
        $conn->close();
    }
}


?>