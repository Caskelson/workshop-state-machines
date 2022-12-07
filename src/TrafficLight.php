<?php

declare(strict_types=1);

namespace App;

use JetBrains\PhpStorm\Pure;

class TrafficLight implements StateAwareInterface
{
    public YellowState|RedState|GreenState $state;

    public TrafficLightTransitions $transitions;

    #[Pure] public function __construct(YellowState|RedState|GreenState $state)
    {
        $this->transitions = new TrafficLightTransitions();
        $this->state = $state;
    }

    public function getState(): YellowState|RedState|GreenState
    {
        return $this->state;
    }

    public function can(string $transition): bool
    {
        return $this->transitions->can($this->state, $transition);
    }

    public function apply(string $transition): void
    {
        $this->state = $this->transitions->move($this->state, $transition);
    }
}