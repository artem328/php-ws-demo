<?php

declare(strict_types=1);

namespace App\Chat;

interface NameGeneratorInterface
{
    public function generateName(): string;
}