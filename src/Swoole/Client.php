<?php

declare(strict_types=1);

namespace App\Swoole;

use App\Chat\AbstractClient;
use Swoole\WebSocket\Server;

final class Client extends AbstractClient
{
    /**
     * @var Server
     */
    private $server;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $id, string $name, Server $server)
    {
        parent::__construct($id, $name);
        $this->server = $server;
    }

    /**
     * {@inheritdoc}
     */
    public function send(string $message): void
    {
        $this->server->push((int) $this->getId(), $message);
    }
}