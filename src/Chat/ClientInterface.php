<?php

declare(strict_types=1);

namespace App\Chat;

interface ClientInterface
{
    public function getId(): string;

    public function getName(): string;

    public function send(string $message): void;
}