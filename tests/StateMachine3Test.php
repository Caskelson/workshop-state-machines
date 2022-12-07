<?php

declare(strict_types=1);

namespace Tests\App;

use App\GreenState;
use App\RedState;
use App\TrafficLight;
use App\StateMachine;
use App\YellowState;
use PHPUnit\Framework\TestCase;

class StateMachine3Test extends TestCase
{
    /** @var StateMachine */
    private $sm;
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider canProvider
     */
    public function testCan(YellowState|RedState|GreenState $currentState, string $transition, bool $result)
    {
        $trafficLight = new TrafficLight($currentState);
        $this->sm = new StateMachine($trafficLight);

        if ($result) {
            $this->assertTrue($this->sm->can($transition), sprintf('We should be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        } else {
            $this->assertFalse($this->sm->can($transition), sprintf('We should NOT be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        }
    }

    /**
     * @dataProvider applyProvider
     */
    public function testApply(YellowState|RedState|GreenState $currentState, YellowState|RedState|GreenState $newState, string $exception = null)
    {
        $trafficLight = new TrafficLight($currentState);
        $this->sm = new StateMachine($trafficLight);

        if (null !== $exception) {
            $this->expectException($exception);
        }

        $this->sm->apply(sprintf('to_%s', $newState));
        $this->assertEquals($newState, $trafficLight->getState(), sprintf('A state machine with state "%s" should have updated to "%s" after ->apply("%s")', $currentState, $newState, $newState));
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
