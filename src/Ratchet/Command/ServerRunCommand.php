<?php

declare(strict_types=1);

namespace App\Ratchet\Command;

use App\Command\AbstractServerRunCommand;
use App\Ratchet\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ServerRunCommand extends AbstractServerRunCommand
{
    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var IoServer|null
     */
    private $server;

    /**
     * {@inheritdoc}
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function onTerminate(OutputInterface $output): void
    {
        if (null === $this->server) {
            return;
        }

        $output->writeln('Stopping server');

        $this->server->loop->stop();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandNamePrefix(): string
    {
        return 'ratchet';
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getOption('port');

        $output->writeln(sprintf('Starting Ratchet server on port %d', $port));

        $this->server = IoServer::factory(
            new HttpServer(new WsServer($this->chat)),
            $port
        );

        $this->server->run();
    }
}
