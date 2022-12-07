<?php

declare(strict_types=1);

namespace App;

class YellowState
{
    private const transitions = ['to_green', 'to_red'];

    public function __toString(): string
    {
        return 'yellow';
    }

    public static function getTransitions(): array
    {
        return self::transitions;
    }
}