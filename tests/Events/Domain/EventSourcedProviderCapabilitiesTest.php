<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AggregateEventStream;
use Becklyn\Ddd\Events\Testing\DomainEventTestTrait;
use Becklyn\Ddd\Identity\Domain\AbstractAggregateId;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTest extends TestCase
{
    use DomainEventTestTrait;

    public function testRaiseAndApplyEventRaisesAndAppliesTheEvent() : void
    {
        $fixture = $this->givenAnEventSourcedProvider();
        $this->givenTheEventSourcedProviderStateHasNotBeenChanged($fixture);
        $this->whenRaiseAndApplyEventSourcedProviderCapabilitiesTestEventIsCalled($fixture);
        $this->thenStateShouldHaveBeenChangedOnTheEventSourcedProvider($fixture);
        $this->thenEventSourcedProviderCapabilitiesTestEventShouldHaveBeenRaised($fixture);
    }

    private function givenAnEventSourcedProvider() : EventSourcedProviderCapabilitiesTestDouble
    {
        return new EventSourcedProviderCapabilitiesTestDouble();
    }

    private function givenTheEventSourcedProviderStateHasNotBeenChanged(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        self::assertFalse($fixture->stateChanged());
    }

    private function whenRaiseAndApplyEventSourcedProviderCapabilitiesTestEventIsCalled(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        $fixture->raiseAndApplyEventSourcedProviderCapabilitiesTestEvent();
    }

    private function thenStateShouldHaveBeenChangedOnTheEventSourcedProvider(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        self::assertTrue($fixture->stateChanged());
    }

    private function thenEventSourcedProviderCapabilitiesTestEventShouldHaveBeenRaised(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        $events = $fixture->dequeueEvents();
        self::assertCount(1, $events);
        self::assertContainsOnlyInstancesOf(EventSourcedProviderCapabilitiesTestEvent::class, $events);
    }

    public function testReconstituteAppliesEventsFromStreamButDoesNotRaiseThem() : void
    {
        $eventStream = $this->givenAggregateEventStreamContainingEventSourcedProviderCapabilitiesTestEvent();
        $fixture = $this->whenReconsituteIsCalledForTheEventSourcedProviderWithTheEventStream($eventStream);
        $this->thenTheEventSourcedProviderShouldHaveBeenReconsitutedWithItsStateChanged($fixture);
        $this->thenNoEventsShouldHaveBeenRaisedOnTheEventSourcedProvcider($fixture);
    }

    private function givenAggregateEventStreamContainingEventSourcedProviderCapabilitiesTestEvent() : AggregateEventStream
    {
        return new AggregateEventStream(
            $this->getMockForAbstractClass(AbstractAggregateId::class, [], '', false),
            Collection::make([new EventSourcedProviderCapabilitiesTestEvent($this->givenAnEventId(), $this->givenARaisedTs())])
        );
    }

    private function whenReconsituteIsCalledForTheEventSourcedProviderWithTheEventStream(
        AggregateEventStream $eventStream
    ) : EventSourcedProviderCapabilitiesTestDouble {
        return EventSourcedProviderCapabilitiesTestDouble::reconstitute($eventStream);
    }

    private function thenTheEventSourcedProviderShouldHaveBeenReconsitutedWithItsStateChanged(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        self::assertTrue($fixture->stateChanged());
    }

    private function thenNoEventsShouldHaveBeenRaisedOnTheEventSourcedProvcider(EventSourcedProviderCapabilitiesTestDouble $fixture) : void
    {
        self::assertEmpty($fixture->dequeueEvents());
    }
}
