<?php declare(strict_types=1);

namespace Becklyn\Ddd\Events\Application;

use Becklyn\Ddd\Events\Domain\EventRegistry;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-05
 */
class EventManager
{
    public function __construct(
        private EventRegistry $eventRegistry,
        private EventBus $eventBus,
    ) {
    }

    /**
     * Dequeues all events from the registry and dispatches them to the event bus. To be used after an application transaction is committed.
     */
    public function flush() : void
    {
        foreach ($this->eventRegistry->dequeueEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }

    /**
     * Dequeues all events from the registry but does nothing with them, effectively deleting them. To be used after an application transaction is rolled back.
     */
    public function clear() : void
    {
        $this->eventRegistry->dequeueEvents();
    }
}
