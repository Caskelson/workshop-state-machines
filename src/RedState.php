<?php

namespace App;

class RedState
{
    private const transitions = ['to_yellow'];

    public function __toString(): string
    {
        return 'red';
    }

    public static function getTransitions(): array
    {
        return self::transitions;
    }
}