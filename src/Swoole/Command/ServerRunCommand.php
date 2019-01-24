<?php

declare(strict_types=1);

namespace App\Swoole\Command;

use App\Command\AbstractServerRunCommand;
use App\Swoole\Chat;
use App\Swoole\Server;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ServerRunCommand extends AbstractServerRunCommand
{
    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var Server|null
     */
    private $server;

    public function __construct(Chat $chat)
    {
        parent::__construct();
        $this->chat = $chat;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandNamePrefix(): string
    {
        return 'swoole';
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $port = $input->getOption('port');

        $output->writeln(sprintf('Starting Swoole server on port %d', $port));

        $this->server = new Server($this->chat, $port);

        $this->server->run();
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

        $this->server->stop();
    }
}