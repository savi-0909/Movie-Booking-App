<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatBot implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message received: $msg\n";

        // Simple bot logic
        $response = $this->getBotResponse($msg);

        foreach ($this->clients as $client) {
            $client->send($response);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function getBotResponse($msg) {
        // Simple bot response logic
        $responses = [
            "hello" => "Hi there! How can I assist you today?",
            "how are you" => "I'm just a bot, but I'm here to help!",
            "what movies are available" => "You can check the movie list on the main page.",
            "bye" => "Goodbye! Have a great day!",
        ];

        $msg = strtolower($msg);
        return $responses[$msg] ?? "Sorry, I didn't understand that. Can you please rephrase?";
    }
}
?>
