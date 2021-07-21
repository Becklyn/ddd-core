<?php

namespace Becklyn\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-03
 */
abstract class AbstractAggregateId extends AbstractEntityId implements AggregateId
{
    public function aggregateType(): string
    {
        return $this->entityType();
    }
}
