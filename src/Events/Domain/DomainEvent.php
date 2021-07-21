<?php

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
interface DomainEvent
{
    public function id(): EventId;

    public function raisedTs(): \DateTimeImmutable;

    public function aggregateId(): AggregateId;

    public function aggregateType(): string;
}
