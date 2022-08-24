<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\EventId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-04-06
 */
class EventIdTest extends TestCase
{
    public function testFromStringAcceptsUuid() : void
    {
        $uuid = Uuid::uuid4()->toString();
        $id = EventId::fromString($uuid);
        self::assertEquals($uuid, $id->asString());
    }

    public function testFromStringThrowsExceptionForNonUuid() : void
    {
        $notUuid = 'foo';
        $this->expectException(\Exception::class);
        EventId::fromString($notUuid);
    }
}
