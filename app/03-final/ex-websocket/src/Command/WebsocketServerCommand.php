<?php

namespace App\Command;

use App\D09Bundle\WebsocketHandler\PostHandler;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'websocket:server',
    description: 'Start the WebSocket server.'
)]
class WebsocketServerCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = 8080;
        $output->writeln("Starting server on port " . $port);
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new PostHandler()
                )
            ),
            $port
        );
        $server->run();
        return Command::SUCCESS;
    }
}



?>