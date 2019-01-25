<?php

declare(strict_types=1);

namespace App\Ratchet;

use App\Chat\ClientStorage;
use App\Chat\Message;
use App\Chat\MessageSender;
use App\Chat\NameGeneratorInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

final class Chat implements MessageComponentInterface
{
    /**
     * @var ClientStorage
     */
    private $clients;

    /**
     * @var MessageSender
     */
    private $messageSender;

    /**
     * @var NameGeneratorInterface
     */
    private $nameGenerator;

    public function __construct(MessageSender $messageSender, NameGeneratorInterface $nameGenerator)
    {
        $this->clients = new ClientStorage();
        $this->messageSender = $messageSender;
        $this->nameGenerator = $nameGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $connectedClient = new Client(
            $this->getConnectionId($conn),
            $this->nameGenerator->generateName(),
            $conn
        );

        $this->clients->addClient($connectedClient);

        $message = Message::connect($connectedClient);

        $this->messageSender->sendMessageToClients($this->clients, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function onClose(ConnectionInterface $conn): void
    {
        $disconnectedClient = $this->clients->getById($this->getConnectionId($conn));

        if (null === $disconnectedClient) {
            return;
        }

        $this->clients->removeClient($disconnectedClient);

        $message = Message::disconnect($disconnectedClient);

        $this->messageSender->sendMessageToClients($this->clients, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $sender = $this->clients->getById($this->getConnectionId($from));

        if (null === $sender) {
            return;
        }

        $message = Message::message($sender, htmlentities($msg));

        $this->messageSender->sendMessageToClients($this->clients, $message);
    }

    private function getConnectionId(ConnectionInterface $connection): string
    {
        return md5((string) spl_object_id($connection));
    }
}
