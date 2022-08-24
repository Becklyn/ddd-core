<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Commands\Domain;

use Becklyn\Ddd\Commands\Domain\CommandId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2022-08-19
 */
class CommandIdTest extends TestCase
{
    public function testFromStringAcceptsUuid() : void
    {
        $uuid = Uuid::uuid4()->toString();
        $id = CommandId::fromString($uuid);
        self::assertEquals($uuid, $id->asString());
    }

    public function testFromStringThrowsExceptionForNonUuid() : void
    {
        $notUuid = 'foo';
        $this->expectException(\Exception::class);
        CommandId::fromString($notUuid);
    }
}
