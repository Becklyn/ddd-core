<?php

namespace Becklyn\Ddd\Tests\Events\Application;

use Becklyn\Ddd\Events\Application\EventBus;
use Becklyn\Ddd\Events\Application\EventManager;
use Becklyn\Ddd\Events\Domain\DomainEvent;
use Becklyn\Ddd\Events\Domain\EventRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class EventManagerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|EventRegistry $eventRegistry;
    private ObjectProphecy|EventBus $eventBus;

    private EventManager $fixture;

    protected function setUp(): void
    {
        $this->eventRegistry = $this->prophesize(EventRegistry::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->fixture = new EventManager($this->eventRegistry->reveal(), $this->eventBus->reveal());
    }

    public function testClearDequeuesRegistryButDoesNotDispatchToBus(): void
    {
        $this->eventRegistry->dequeueEvents()->shouldBeCalledTimes(1);
        $this->eventBus->dispatch(Argument::any())->shouldNotBeCalled();
        $this->fixture->clear();
    }

    public function testFlushDequeuesRegistryAndDispatchesDequeuedEventsToBus(): void
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        $this->eventRegistry->dequeueEvents()->willReturn([$event]);
        $this->eventRegistry->dequeueEvents()->shouldBeCalledTimes(1);
        $this->eventBus->dispatch($event)->shouldBeCalledTimes(1);
        $this->fixture->flush();
    }
}
