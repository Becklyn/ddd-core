<?php declare(strict_types=1);

namespace Becklyn\Ddd\Events\Domain;

use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-07-29
 */
trait EventCreatorCapabilities
{
    protected function nextEventIdentity() : EventId
    {
        return EventId::fromString(Uuid::uuid4()->toString());
    }
}
