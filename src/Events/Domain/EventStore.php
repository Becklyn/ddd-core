<?php declare(strict_types=1);

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-08-21
 */
interface EventStore
{
    public function append(DomainEvent $event) : void;

    public function getAggregateStream(AggregateId $aggregateId) : AggregateEventStream;
}
