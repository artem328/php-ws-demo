<?php

declare(strict_types=1);

namespace App\Chat;

interface MessageInterface
{
    public const TYPE_CONNECT = 'connect';
    public const TYPE_DISCONNECT = 'disconnect';
    public const TYPE_MESSAGE = 'message';

    public function getType(): string;

    public function getClient(): ClientInterface;

    public function getText(): string;

    public function getTime(): \DateTimeImmutable;
}