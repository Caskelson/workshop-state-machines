<?php

declare(strict_types=1);

namespace App;

class TrafficLightTransitions
{
    public const LIST = [
        'GREEN' => 'to_green',
        'YELLOW' => 'to_yellow',
        'RED' => 'to_red'
    ];

    /**
     * Check if we are allowed to apply $state right now. Ie, is there a transition
     * from $this->state to $state?
     */
    public function can(GreenState|RedState|YellowState $state, string $transition): bool
    {
        if (!in_array($transition, self::LIST)) {
            return false;
        }

        if (in_array($transition, $state->getTransitions(), true)) {
            return true;
        }

        return false;
    }

    /**
     * This will update $this->state.
     *
     * @throws \InvalidArgumentException if the $newState is invalid.
     */
    public function move($state, string $transition): GreenState|RedState|YellowState|null
    {
        if (!$this->can($state, $transition)) {
            throw new \InvalidArgumentException();
        }

        return match ($transition) {
            self::LIST['GREEN'] => new GreenState(),
            self::LIST['YELLOW'] => new YellowState(),
            self::LIST['RED'] => new RedState(),
            default => null,
        };
    }
}