<?php

declare(strict_types=1);

namespace App;

class StateMachine
{
    public function __construct(
        private StateAwareInterface $system
    ) {
    }

    public function can(string $transition): bool
    {
        return $this->system->can($transition);
    }

    public function apply(string $transition): void
    {
        $this->system->apply($transition);
    }
}
