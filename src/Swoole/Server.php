<?php

declare(strict_types=1);

namespace App\Swoole;

use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as SwooleServer;

final class Server
{
    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var SwooleServer
     */
    private $swooleServer;

    public function __construct(Chat $chat, int $port, string $host = null)
    {
        $this->chat = $chat;
        $this->swooleServer = new SwooleServer($host ?? '0.0.0.0', $port);
        $this->initCallbacks();
    }

    public function run(): void
    {
        $this->swooleServer->start();
    }

    public function stop(): void
    {
        $this->swooleServer->stop();
    }

    private function initCallbacks(): void
    {
        $this->swooleServer->on('open', function (SwooleServer $server, Request $request) {
            $this->chat->onOpen($server, $request);
        });

        $this->swooleServer->on('message', function (SwooleServer $server, Frame $frame) {
            $this->chat->onMessage($server, $frame);
        });

        $this->swooleServer->on('close', function (SwooleServer $server, int $fd) {
            $this->chat->onClose($server, (string) $fd);
        });
    }
}
