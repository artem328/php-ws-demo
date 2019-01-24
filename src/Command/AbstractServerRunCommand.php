<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractServerRunCommand extends Command
{
    protected const DEFAULT_PORT = 8080;

    abstract protected function getCommandNamePrefix(): string;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName(sprintf('%s:server:run', $this->getCommandNamePrefix()))
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'Port, server will be started on',
                static::DEFAULT_PORT)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        if (function_exists('pcntl_signal')) {
            $onTerminate = function () use ($output) {
                $this->onTerminate($output);
            };
            pcntl_signal(SIGINT, $onTerminate);
            pcntl_signal(SIGTERM, $onTerminate);
        }
    }

    protected function onTerminate(OutputInterface $output): void
    {
    }
}
