<?php

declare(strict_types=1);

namespace App\Ratchet;

use App\Chat\ClientStorage;
use App\Chat\Message;
use App\Chat\MessageInterface;
use App\Chat\MessageSender;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

final class Chat implements MessageComponentInterface
{
    /**
     * @var int
     */
    private static $count = 1;

    /**
     * @var ClientStorage
     */
    private $clients;

    /**
     * @var MessageSender
     */
    private $messageSender;

    public function __construct(MessageSender $messageSender)
    {
        $this->clients = new ClientStorage();
        $this->messageSender = $messageSender;
    }

    /**
     * {@inheritdoc}
     */
    function onOpen(ConnectionInterface $conn): void
    {
        $connectedClient = new Client($this->getConnectionId($conn), sprintf('User %d', self::$count++), $conn);

        $this->clients->addClient($connectedClient);

        $message = new Message(MessageInterface::TYPE_CONNECT, $connectedClient);

        foreach ($this->clients as $client) {
            $this->messageSender->sendMessageToClient($client, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    function onClose(ConnectionInterface $conn): void
    {
        $disconnectedClient = $this->clients->getById($this->getConnectionId($conn));

        if (null === $disconnectedClient) {
            return;
        }

        $this->clients->removeClient($disconnectedClient);

        $message = new Message(MessageInterface::TYPE_DISCONNECT, $disconnectedClient);

        foreach ($this->clients as $client) {
            $this->messageSender->sendMessageToClient($client, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    function onError(ConnectionInterface $conn, \Exception $e): void
    {
    }

    /**
     * {@inheritdoc}
     */
    function onMessage(ConnectionInterface $from, $msg): void
    {
        $sender = $this->clients->getById($this->getConnectionId($from));

        if (null === $sender) {
            return;
        }

        $message = new Message(MessageInterface::TYPE_MESSAGE, $sender, $msg);

        foreach ($this->clients as $client) {
            $this->messageSender->sendMessageToClient($client, $message);
        }
    }

    private function getConnectionId(ConnectionInterface $connection): string
    {
        return md5((string) spl_object_id($connection));
    }
}