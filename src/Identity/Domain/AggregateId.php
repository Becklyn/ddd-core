<?php

namespace Becklyn\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-02
 */
interface AggregateId extends EntityId
{
    public function aggregateType(): string;
}
