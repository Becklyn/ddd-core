<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\EventProvider;
use Becklyn\Ddd\Events\Domain\EventSourcedProviderCapabilities;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTestDouble implements EventProvider
{
    use EventSourcedProviderCapabilities;

    private bool $stateChanged = false;

    public function stateChanged() : bool
    {
        return $this->stateChanged;
    }

    public function raiseAndApplyEventSourcedProviderCapabilitiesTestEvent() : void
    {
        $this->raiseAndApplyEvent(new EventSourcedProviderCapabilitiesTestEvent($this->nextEventIdentity(), new \DateTimeImmutable()));
    }

    private function applyEventSourcedProviderCapabilitiesTestEvent(EventSourcedProviderCapabilitiesTestEvent $event) : void
    {
        $this->stateChanged = true;
    }
}
