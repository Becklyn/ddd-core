<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AbstractDomainEvent;
use Becklyn\Ddd\Events\Domain\DomainEvent;
use Becklyn\Ddd\Events\Domain\EventId;
use Becklyn\Ddd\Events\Domain\EventProviderCapabilities;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-04-06
 */
class EventProviderCapabilitiesTest extends TestCase
{
    public function testDequeueEventsReturnsRaisedEvents() : void
    {
        /** @var MockObject|EventProviderCapabilities $eventProviderCapabilities */
        $eventProviderCapabilities = $this->getMockForTrait(EventProviderCapabilities::class);
        $reflection = new \ReflectionClass(\get_class($eventProviderCapabilities));
        $method = $reflection->getMethod('raiseEvent');
        $method->setAccessible(true);

        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()->toString()), new \DateTimeImmutable()]);

        $method->invokeArgs($eventProviderCapabilities, [$event]);
        $result = $eventProviderCapabilities->dequeueEvents();
        self::assertContainsOnlyInstancesOf(DomainEvent::class, $result);
        self::assertCount(1, $result);
        self::assertSame($event, $result[0]);
    }

    public function testDequeueEventsRemovesRaisedEventsFromEventProvider() : void
    {
        /** @var MockObject|EventProviderCapabilities $eventProviderCapabilities */
        $eventProviderCapabilities = $this->getMockForTrait(EventProviderCapabilities::class);
        $reflection = new \ReflectionClass(\get_class($eventProviderCapabilities));
        $method = $reflection->getMethod('raiseEvent');
        $method->setAccessible(true);

        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()->toString()), new \DateTimeImmutable()]);

        $method->invokeArgs($eventProviderCapabilities, [$event]);
        $events = $eventProviderCapabilities->dequeueEvents();
        self::assertNotEmpty($events);
        $result = $eventProviderCapabilities->dequeueEvents();
        self::assertEmpty($result);
    }
}
