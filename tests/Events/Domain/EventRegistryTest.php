<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\DomainEvent;
use Becklyn\Ddd\Events\Domain\EventProvider;
use Becklyn\Ddd\Events\Domain\EventRegistry;
use Becklyn\Ddd\Events\Domain\EventStore;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-06
 */
class EventRegistryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|EventStore $eventStore;

    private EventRegistry $fixture;

    protected function setUp(): void
    {
        $this->eventStore = $this->prophesize(EventStore::class);
        $this->fixture = new EventRegistry($this->eventStore->reveal());
    }

    public function testRegisterEvent(): void
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        $this->fixture->registerEvent($event);
        $events = $this->fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
        $this->eventStore->append($event)->shouldHaveBeenCalledTimes(1);
    }

    public function testDequeueProviderAndRegister(): void
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        /** @var EventProvider|ObjectProphecy $provider */
        $provider = $this->prophesize(EventProvider::class);
        $provider->dequeueEvents()->willReturn([$event]);
        $this->fixture->dequeueProviderAndRegister($provider->reveal());
        $events = $this->fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
        $this->eventStore->append($event)->shouldHaveBeenCalledTimes(1);
    }
}
