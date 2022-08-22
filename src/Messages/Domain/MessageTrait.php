<?php

namespace Becklyn\Ddd\Messages\Domain;

use Becklyn\Ddd\Commands\Domain\CommandId;
use Becklyn\Ddd\Events\Domain\EventId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2022-08-18
 */
trait MessageTrait
{
    protected ?string $correlationId = null;
    protected ?string $correlationType = null;

    protected ?string $causationId = null;
    protected ?string $causationType = null;

    /**
     * @throws CorrelationException
     */
    public function correlationId(): MessageId
    {
        if (empty($this->correlationType) || empty($this->correlationId)) {
            throw new CorrelationException("Attempting to call correlationId() on an event which hasn't been correlated yet");
        }

        if ($this->correlationType === EventId::class) {
            return EventId::fromString($this->correlationId);
        }

        if ($this->correlationType === CommandId::class) {
            return CommandId::fromString($this->correlationId);
        }

        throw new CorrelationException("Unknown correlation type: {$this->correlationType}");
    }

    /**
     * @throws CorrelationException
     */
    public function causationId(): MessageId
    {
        if (empty($this->causationType) || empty($this->causationId)) {
            throw new CorrelationException("Attempting to call causationId() on an event which hasn't been correlated yet");
        }

        if ($this->causationType === EventId::class) {
            return EventId::fromString($this->causationId);
        }

        if ($this->causationType === CommandId::class) {
            return CommandId::fromString($this->causationId);
        }

        throw new CorrelationException("Unknown causation type: {$this->causationType}");
    }

    public function correlateWith(Message $message): void
    {
        $this->correlationId = $message->correlationId()->asString();
        $this->correlationType = get_class($message->correlationId());

        $this->causationId = $message->id()->asString();
        $this->causationType = get_class($message->id());
    }
}
