<?php

namespace Becklyn\Ddd\Commands\Application;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
interface CommandBus
{
    public function dispatch($command): void;
}
