<?php

declare(strict_types=1);

namespace App\Chat;

final class ClientStorage implements \Countable, \Iterator
{
    /**
     * @var ClientInterface[]
     */
    private $clients = [];

    public function addClient(ClientInterface $client): void
    {
        if (false === array_search($client, $this->clients, true)) {
            $this->clients[] = $client;
        }
    }

    public function removeClient(ClientInterface $client): void
    {
        if (false !== ($index = array_search($client, $this->clients, true))) {
            array_splice($this->clients, $index, 1);
        }
    }

    /**
     * @return  ClientInterface[]
     */
    public function getAll(): array
    {
        return $this->clients;
    }

    public function getById(string $id): ?ClientInterface
    {
        foreach ($this->clients as $client) {
            if ($client->getId() === $id) {
                return $client;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): ClientInterface
    {
        return current($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        next($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return key($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return false !== current($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        reset($this->clients);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->clients);
    }
}