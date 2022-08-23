<?php

namespace Becklyn\Ddd\Events\Domain;

use Becklyn\Ddd\Messages\Domain\Message;

/**
 * This service must be used in domain services to gather all of the events raised during their execution. The event manager will eventually dequeue the
 * registry and dispatch all registered events to an event bus after the application transaction is committed.
 *
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
class EventRegistry implements EventProvider
{
    use EventProviderCapabilities;

    public function __construct(
        private EventStore $eventStore,
    ) {
    }

    /**
     * Adds a domain event to its list of registered events.
     */
    public function registerEvent(DomainEvent $event, Message $messageToCorrelateWith): void
    {
        $event->correlateWith($messageToCorrelateWith);
        $this->raiseEvent($event);
        $this->eventStore->append($event);
    }

    /**
     * Dequeues an EventProvider and registers all of its events.
     */
    public function dequeueProviderAndRegister(EventProvider $eventProvider, Message $messageToCorrelateWith): void
    {
        foreach ($eventProvider->dequeueEvents() as $event) {
            $this->registerEvent($event, $messageToCorrelateWith);
        }
    }
}
