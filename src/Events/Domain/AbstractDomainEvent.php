<?php

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Utilities\Collections\IterableToCollectionConstructionTrait;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
abstract class AbstractDomainEvent implements DomainEvent
{
    use IterableToCollectionConstructionTrait;

    protected EventId $id;

    protected \DateTimeImmutable $raisedTs;

    public function __construct(EventId $id, \DateTimeImmutable $raisedTs)
    {
        $this->id = $id;
        $this->raisedTs = $raisedTs;
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function raisedTs(): \DateTimeImmutable
    {
        return $this->raisedTs;
    }
}
