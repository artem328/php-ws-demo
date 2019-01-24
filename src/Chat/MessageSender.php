<?php

declare(strict_types=1);

namespace App\Chat;

use Symfony\Component\Serializer\SerializerInterface;

final class MessageSender
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function sendMessageToClient(ClientInterface $client, MessageInterface $message): void
    {
        $client->send($this->serializer->serialize($message, 'json'));
    }

    public function sendMessageToClients(ClientStorage $clients, MessageInterface $message): void
    {
        foreach ($clients as $client) {
            $this->sendMessageToClient($client, $message);
        }
    }
}