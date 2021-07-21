<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AbstractDomainEvent;
use Becklyn\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTestEvent extends AbstractDomainEvent
{
    public function aggregateId(): AggregateId
    {
        // TODO: Implement aggregateId() method.
    }

    public function aggregateType(): string
    {
        // TODO: Implement aggregateType() method.
    }
}
