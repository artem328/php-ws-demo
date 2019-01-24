<?php

declare(strict_types=1);

namespace App\Ratchet\Command;

use App\Ratchet\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ServerRunCommand extends Command implements EventSubscriberInterface
{
    private const DEFAULT_PORT = 8080;

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var IoServer|null
     */
    private $server;

    /**
     * {@inheritdoc}
     */
    public function __construct(Chat $chat, EventDispatcherInterface $eventDispatcher)
    {
        $this->chat = $chat;
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::TERMINATE => 'closeConnection',
            ConsoleEvents::ERROR => 'closeConnection',
        ];
    }

    public function closeConnection(ConsoleEvent $event): void
    {
        if (null === $this->server) {
            return;
        }

        $event->getOutput()->writeln('Stopping server');

        $this->server->loop->stop();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('ratchet:server:run')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'Port, server will be started on', self::DEFAULT_PORT)
        ;

        $this->eventDispatcher->addSubscriber($this);
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