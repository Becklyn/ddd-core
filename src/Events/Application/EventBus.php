<?php declare(strict_types=1);

namespace Becklyn\Ddd\Events\Application;

use Becklyn\Ddd\Events\Domain\DomainEvent;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-05
 */
interface EventBus
{
    public function dispatch(DomainEvent $event) : void;
}
