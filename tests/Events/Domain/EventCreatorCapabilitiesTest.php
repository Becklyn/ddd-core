<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\EventCreatorCapabilities;
use Becklyn\Ddd\Events\Domain\EventId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-06
 */
class EventCreatorCapabilitiesTest extends TestCase
{
    public function testNextEventIdentityReturnsEventId(): void
    {
        /** @var MockObject|EventCreatorCapabilities $event */
        $EventCreatorCapabilities = $this->getMockForTrait(EventCreatorCapabilities::class);
        $reflection = new \ReflectionClass(get_class($EventCreatorCapabilities));
        $method = $reflection->getMethod('nextEventIdentity');
        $method->setAccessible(true);

        $result = $method->invoke($EventCreatorCapabilities);
        $this->assertInstanceOf(EventId::class, $result);
    }
}
