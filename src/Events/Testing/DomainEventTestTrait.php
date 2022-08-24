<?php

namespace Becklyn\Ddd\Events\Testing;

use Becklyn\Ddd\Events\Application\EventBus;
use Becklyn\Ddd\Events\Domain\AggregateEventStream;
use Becklyn\Ddd\Events\Domain\EventId;
use Becklyn\Ddd\Events\Domain\EventProvider;
use Becklyn\Ddd\Events\Domain\EventRegistry;
use Becklyn\Ddd\Events\Domain\EventStore;
use Becklyn\Ddd\Messages\Domain\Message;
use Ramsey\Uuid\Uuid;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-07-23
 *
 * @codeCoverageIgnore
 */
trait DomainEventTestTrait
{
    protected ObjectProphecy|EventRegistry $eventRegistry;
    protected ObjectProphecy|EventBus $eventBus;
    protected ObjectProphecy|EventStore $eventStore;

    protected function initDomainEventTestTrait(): void
    {
        $this->eventRegistry = $this->prophesize(EventRegistry::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->eventStore = $this->prophesize(EventStore::class);
    }

    protected function givenAnEventId(): EventId
    {
        return EventId::fromString(Uuid::uuid4());
    }

    protected function givenARaisedTs(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    /**
     * @param EventProvider|Argument $eventProvider
     */
    protected function thenEventRegistryShouldDequeueAndRegister($eventProvider, Message $correlationMessage = null): void
    {
        $correlationMessage = $correlationMessage ?? Argument::any();
        $this->eventRegistry->dequeueProviderAndRegister($eventProvider, $correlationMessage)->shouldBeCalled();
    }

    protected function thenEventShouldBeDispatched($event): void
    {
        $this->eventBus->dispatch($event)->shouldBeCalled();
    }

    protected function thenEventShouldBeDispatchedTimes($event, int $times): void
    {
        $this->eventBus->dispatch($event)->shouldBeCalledTimes($times);
    }

    protected function thenEventShouldNotBeDispatched($event): void
    {
        $this->eventBus->dispatch($event)->shouldNotBeCalled();
    }

    protected function thenNoEventsShouldBeDispatched(): void
    {
        $this->eventBus->dispatch(Argument::any())->shouldNotBeCalled();
    }

    protected function givenEventRegistryDequeuesAndRegisters(EventProvider $eventProvider, Message $correlationMessage = null): void
    {
        $correlationMessage = $correlationMessage ?? Argument::any();
        $this->eventRegistry->dequeueProviderAndRegister($eventProvider, $correlationMessage);
    }

    protected function thenEventRegistryShouldNotDequeueAndRegisterAnything(): void
    {
        $this->eventRegistry->dequeueProviderAndRegister(Argument::any(), Argument::any())->shouldNotBeCalled();
    }

    protected function givenEventRegistryThrowsExceptionOnDequeueAndRegister(EventProvider $eventProvider, Message $correlationMessage = null): \Exception
    {
        $correlationMessage = $correlationMessage ?? Argument::any();
        $exception = new \Exception();
        $this->eventRegistry->dequeueProviderAndRegister($eventProvider, $correlationMessage)->willThrow($exception);
        return $exception;
    }

    protected function thenEventRegistryShouldRegister($event, Message $correlationMessage = null): void
    {
        $correlationMessage = $correlationMessage ?? Argument::any();
        $this->eventRegistry->registerEvent($event, $correlationMessage)->shouldBeCalled();
    }

    protected function thenEventRegistryShouldNotRegister($event, Message $correlationMessage = null): void
    {
        $correlationMessage = $correlationMessage ?? Argument::any();
        $this->eventRegistry->registerEvent($event, $correlationMessage)->shouldNotBeCalled();
    }

    protected function thenEventRegistryShouldNotRegisterAnyEvents(): void
    {
        $this->thenEventRegistryShouldNotRegister(Argument::any());
    }

    protected function thenEventStoreShouldAppend($event): void
    {
        $this->eventStore->append($event)->shouldBeCalled();
    }

    protected function thenEventStoreShouldReturnAggregateEventStream(AggregateEventStream $aggregateEventStream): void
    {
        $this->eventStore->getAggregateStream($aggregateEventStream->aggregateId())->willReturn($aggregateEventStream);
    }
}
