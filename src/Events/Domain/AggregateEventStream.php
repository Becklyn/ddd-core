<?php

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Ddd\Identity\Domain\AggregateId;
use Illuminate\Support\Collection;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-11-20
 */
class AggregateEventStream
{
    private AggregateId $aggregateId;
    /** @var Collection|DomainEvent[] */
    private Collection $events;

    public function __construct(AggregateId $aggregateId, Collection $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $events;
    }

    public function aggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    /**
     * @return Collection|DomainEvent[]
     */
    public function events(): Collection
    {
        return $this->events;
    }
}
