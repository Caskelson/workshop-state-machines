<?php

declare(strict_types=1);

namespace Tests\App;

use App\GreenState;
use App\RedState;
use App\TrafficLightStateMachine;
use App\YellowState;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;

class StateMachine1Test extends TestCase
{
    /**
     * @dataProvider canProvider
     */
    public function testCan($currentState, string $transition, bool $result)
    {
        $sm = new TrafficLightStateMachine();

        NSA::setProperty($sm, 'state', $currentState);
        if ($result) {
            $this->assertTrue($sm->can($transition), sprintf('We should be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        } else {
            $this->assertFalse($sm->can($transition), sprintf('We should NOT be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        }
    }

    /**
     * @dataProvider applyProvider
     */
    public function testApply($currentState, $newState, string $exception = null)
    {
        $sm = new TrafficLightStateMachine();
        if (null !== $exception) {
            $this->expectException($exception);
        }

        NSA::setProperty($sm, 'state', $currentState);
        $sm->apply(sprintf('to_%s', $newState));
        if (null === $exception) {
            $this->assertEquals($newState, NSA::getProperty($sm, 'state'), sprintf('A state machine with state "%s" should have updated to "%s" after ->apply("%s")', $currentState, $newState, $newState));
        }
    }

    public function canProvider()
    {
        return [
            [new GreenState(), 'to_green', false],
            [new GreenState(), 'to_yellow', true],
            [new GreenState(), 'to_red', false],
            [new YellowState(), 'to_green', true],
            [new YellowState(), 'to_yellow', false],
            [new YellowState(), 'to_red', true],
            [new RedState(), 'to_green', false],
            [new RedState(), 'to_yellow', true],
            [new RedState(), 'to_red', false],
            [new RedState(), 'foobar', false],
        ];
    }

    public function applyProvider()
    {
        return [
            [new GreenState(), new GreenState(), \InvalidArgumentException::class],
            [new GreenState(), new YellowState()],
            [new GreenState(), new RedState(), \InvalidArgumentException::class],
            [new YellowState(), new GreenState()],
            [new YellowState(), new YellowState(), \InvalidArgumentException::class],
            [new YellowState(), new RedState()],
            [new RedState(), new GreenState(), \InvalidArgumentException::class],
            [new RedState(), new YellowState()],
            [new RedState(), new RedState(), \InvalidArgumentException::class],
        ];
    }
}
