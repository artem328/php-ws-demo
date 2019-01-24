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