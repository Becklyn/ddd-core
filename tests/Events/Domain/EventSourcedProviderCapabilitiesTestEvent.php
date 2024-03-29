<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AbstractDomainEvent;
use Becklyn\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTestEvent extends AbstractDomainEvent
{
    public function aggregateId() : AggregateId
    {
    }

    public function aggregateType() : string
    {
    }
}
