<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AbstractDomainEvent;
use Becklyn\Ddd\Events\Domain\DomainEvent;
use Becklyn\Ddd\Events\Domain\EventId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-04-06
 */
class AbstractDomainEventTest extends TestCase
{
    public function testRaisedTsReturnsRaisedTsPassedToConstructor() : void
    {
        $raisedTs = new \DateTimeImmutable();
        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()->toString()), $raisedTs]);
        self::assertSame($raisedTs, $event->raisedTs());
    }

    public function testIdReturnsIdPassedToConstructor() : void
    {
        $id = EventId::fromString(Uuid::uuid4()->toString());
        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [$id, new \DateTimeImmutable()]);
        self::assertSame($id, $event->id());
    }
}
