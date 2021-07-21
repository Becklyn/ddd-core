<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\EventId;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-06
 */
class EventIdTest extends TestCase
{
    public function testFromStringAcceptsUuid(): void
    {
        $uuid = Uuid::uuid4();
        $id = EventId::fromString($uuid);
        $this->assertEquals($uuid, $id->asString());
    }

    public function testFromStringThrowsExceptionForNonUuid(): void
    {
        $notUuid = 'foo';
        $this->expectException(\Exception::class);
        EventId::fromString($notUuid);
    }
}
