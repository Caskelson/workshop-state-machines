<?php

declare(strict_types=1);

namespace App\Workflow;

use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Transition;

class TrafficLightFactory
{
    public static function create(): StateMachine
    {
        $definitionBuilder = new DefinitionBuilder();
        $definition = $definitionBuilder->addPlaces(['green', 'yellow', 'red'])
            ->addTransition(new Transition('to_green', ['yellow'], ['green']))
            ->addTransition(new Transition('to_yellow', ['green', 'red'], ['yellow']))
            ->addTransition(new Transition('to_red', ['yellow'], ['red']))
            ->build()
        ;

        $marking = new MethodMarkingStore(true, 'currentState');
        return new StateMachine($definition, $marking);
    }
}
