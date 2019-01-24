<?php

declare(strict_types=1);

namespace App\Ratchet;

use App\Chat\AbstractClient;
use Ratchet\ConnectionInterface;

final class Client extends AbstractClient
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $id, string $name, ConnectionInterface $connection)
    {
        parent::__construct($id, $name);
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function send(string $message): void
    {
        $this->connection->send($message);
    }
}