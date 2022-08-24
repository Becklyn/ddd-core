<?php declare(strict_types=1);

namespace Becklyn\Ddd\Events\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-05
 */
trait EventProviderCapabilities
{
    use EventCreatorCapabilities;

    /**
     * @var DomainEvent[]
     */
    private array $domainEvents = [];

    protected function raiseEvent(DomainEvent $event) : void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * Removes all raised events from the provider and returns them.
     *
     * @return DomainEvent[]
     */
    public function dequeueEvents() : array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
