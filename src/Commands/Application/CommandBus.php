<?php

namespace Becklyn\Ddd\Commands\Application;

use Becklyn\Ddd\Commands\Domain\Command;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
interface CommandBus
{
    public function dispatch(Command $command): void;
}
