<?php

declare(strict_types=1);

namespace App\Chat;

class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTimeImmutable
     */
    private $time;

    public function __construct(
        string $type,
        ClientInterface $client,
        string $text = '',
        ?\DateTimeImmutable $time = null
    ) {
        $this->type = $type;
        $this->client = $client;
        $this->text = $text;
        $this->time = $time ?? new \DateTimeImmutable();
    }

    public static function connect(ClientInterface $client): Message
    {
        return new Message(MessageInterface::TYPE_CONNECT, $client);
    }

    public static function disconnect(ClientInterface $client): Message
    {
        return new Message(MessageInterface::TYPE_DISCONNECT, $client);
    }

    public static function message(ClientInterface $client, string $text): Message
    {
        return new Message(MessageInterface::TYPE_MESSAGE, $client, $text);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }
}