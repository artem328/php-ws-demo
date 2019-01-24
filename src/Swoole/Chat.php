<?php

declare(strict_types=1);

namespace App\Swoole;

use App\Chat\ClientStorage;
use App\Chat\Message;
use App\Chat\MessageSender;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\Websocket\Server;

final class Chat
{
    /**
     * @var MessageSender
     */
    private $messageSender;

    public function __construct(MessageSender $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    public function onOpen(Server $server, Request $request): void
    {
        $clients = $this->getClientsFromServer($server);

        $connectedClient = $clients->getById((string) $request->fd);

        if (null === $connectedClient) {
            return;
        }

        $message = Message::connect($connectedClient);

        $this->messageSender->sendMessageToClients($clients, $message);
    }

    public function onClose(Server $server, string $fd): void
    {
        $clients = $this->getClientsFromServer($server);
        $disconnectedClient = $this->createClient($server, $fd);

        $message = Message::disconnect($disconnectedClient);

        $this->messageSender->sendMessageToClients($clients, $message);
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        $clients = $this->getClientsFromServer($server);

        $sender = $clients->getById((string) $frame->fd);

        if (null === $sender) {
            return;
        }

        $message = Message::message($sender, htmlentities($frame->data));

        $this->messageSender->sendMessageToClients($clients, $message);
    }

    private function getClientsFromServer(Server $server): ClientStorage
    {
        $clientStorage = new ClientStorage();

        foreach ($server->connection_list() as $fd) {
            $clientStorage->addClient($this->createClient($server, (string) $fd));
        }

        return $clientStorage;
    }

    private function createClient(Server $server, string $fd): Client
    {
        return new Client((string) $fd, sprintf('User #%d', $fd), $server);
    }
}
