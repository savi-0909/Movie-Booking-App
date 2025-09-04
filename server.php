<?php
require 'vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use MyApp\ChatBot;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatBot()
        )
    ),
    8080
);

$server->run();
?>