<?php

declare(strict_types=1);

namespace App\Chat;

final class SequentialNameGenerator implements NameGeneratorInterface
{
    private static $count = 1;

    /**
     * {@inheritdoc}
     */
    public function generateName(): string
    {
        return sprintf('User #%d', self::$count++);
    }
}