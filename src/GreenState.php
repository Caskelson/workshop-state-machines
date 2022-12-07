<?php

declare(strict_types=1);

namespace App;

class GreenState
{
    private const transitions = ['to_yellow'];

    public function __toString(): string
    {
        return 'green';
    }

    public static function getTransitions(): array
    {
        return self::transitions;
    }
}