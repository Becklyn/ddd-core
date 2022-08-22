<?php

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Ddd\Identity\Domain\AggregateId;
use Becklyn\Ddd\Messages\Domain\Message;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
interface DomainEvent extends Message
{
    public function id(): EventId;

    public function raisedTs(): \DateTimeImmutable;

    public function aggregateId(): AggregateId;

    public function aggregateType(): string;
}
