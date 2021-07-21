<?php

namespace Becklyn\Ddd\Commands\Testing;

use Becklyn\Ddd\Commands\Application\CommandBus;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-07-28
 */
trait CommandBusTestTrait
{
    protected ObjectProphecy|CommandBus $commandbus;

    protected function initCommandBusTestTrait(): void
    {
        $this->commandbus = $this->prophesize(CommandBus::class);
    }

    protected function thenCommandBusShouldDispatch($command): void
    {
        $this->commandbus->dispatch($command)->shouldBeCalled();
    }

    protected function thenCommandBusShouldNotDispatch($command): void
    {
        $this->commandbus->dispatch($command)->shouldNotBeCalled();
    }

    protected function thenCommandBusShouldNotDispatchAnyCommands(): void
    {
        $this->thenCommandBusShouldNotDispatch(Argument::any());
    }

    protected function givenCommandBusThrowsExceptionWhenDispatching($command, \Exception $exception): void
    {
        $this->commandbus->dispatch($command)->willThrow($exception);
    }
}
