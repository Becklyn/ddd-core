<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Commands\Domain\Command;
use Becklyn\Ddd\Events\Domain\DomainEvent;
use Becklyn\Ddd\Events\Domain\EventProvider;
use Becklyn\Ddd\Events\Domain\EventRegistry;
use Becklyn\Ddd\Events\Domain\EventStore;
use Becklyn\Ddd\Messages\Domain\Message;
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

    public function testRegisterEventAddsEventToListOfEvents(): void
    {
        $event = $this->givenAMockEvent()->reveal();
        $this->whenEventIsRegistered($event, $this->givenAMockCommand()->reveal());
        $this->thenEventShouldHaveBeenAddedToList($event);
    }

    private function givenAMockEvent () : ObjectProphecy|DomainEvent
    {
        return $this->prophesize(DomainEvent::class);
    }

    private function givenAMockCommand () : ObjectProphecy|Command
    {
        return $this->prophesize(Command::class);
    }

    private function whenEventIsRegistered (DomainEvent $event, Message $message) : void
    {
        $this->fixture->registerEvent($event, $message);
    }

    private function thenEventShouldHaveBeenAddedToList (DomainEvent $event) : void
    {
        $events = $this->fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
    }

    public function testRegisterEventAppendsEventToEventStore(): void
    {
        $event = $this->givenAMockEvent()->reveal();
        $this->whenEventIsRegistered($event, $this->givenAMockCommand()->reveal());
        $this->thenEventShouldHaveBeenAppendedToEventStore($event);
    }

    private function thenEventShouldHaveBeenAppendedToEventStore (DomainEvent $event) : void
    {
        $this->eventStore->append($event)->shouldHaveBeenCalledTimes(1);
    }

    public function testRegisterEventCorrelatesEventBeforeAddingItToListAndAppendingItToEventStore() : void
    {
        $event = $this->givenAMockEvent();
        $command = $this->givenAMockCommand()->reveal();

        $this->thenEventShouldBeCorrelatedWithCommandBeforeBeingAppendedToEventStore($event, $command);

        $this->whenEventIsRegistered($event->reveal(), $command);
        $this->thenEventShouldHaveBeenCorrelatedWithCommand($event, $command);
        $this->thenEventShouldHaveBeenAddedToList($event);
    }

    private function thenEventShouldBeCorrelatedWithCommandBeforeBeingAppendedToEventStore (ObjectProphecy|DomainEvent $event, Command $command) : void
    {
        $eventStore = $this->eventStore;
        $event->correlateWith($command)->will(function () use ($eventStore, $event) {
            $eventStore->append($event->reveal())->shouldBeCalledOnce();
            return;
        });
    }

    private function thenEventShouldHaveBeenCorrelatedWithCommand (ObjectProphecy|DomainEvent $event, Command $command) : void
    {
        $event->correlateWith($command)->shouldHaveBeenCalledTimes(1);
    }

    public function testDequeueProviderAndRegisterDequeuesEventsFromProviderCorrelatesThemWithCommandAndRegistersThem(): void
    {
        $event = $this->givenAMockEvent();
        $command = $this->givenAMockCommand()->reveal();

        $provider = $this->givenAnEventProviderContainingTheEvent($event->reveal())->reveal();

        $this->whenProviderIsDequeuedAndRegistered($provider, $command);

        $this->thenEventShouldHaveBeenCorrelatedWithCommand($event, $command);

        $this->thenEventShouldHaveBeenAddedToList($event->reveal());
        $this->thenEventShouldHaveBeenAppendedToEventStore($event->reveal());
    }

    private function givenAnEventProviderContainingTheEvent (DomainEvent $event) : ObjectProphecy|EventProvider
    {
        /** @var EventProvider|ObjectProphecy $provider */
        $provider = $this->prophesize(EventProvider::class);
        $provider->dequeueEvents()->willReturn([$event]);
        return $provider;
    }

    private function whenProviderIsDequeuedAndRegistered (EventProvider $provider, Command $command) : void
    {
        $this->fixture->dequeueProviderAndRegister($provider, $command);
    }
}
