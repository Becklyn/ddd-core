<?php

namespace Becklyn\Ddd\Tests\Commands\Application;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-27
 */
interface CommandHandlerTestPostRollbackExecutor
{
    public function execute(\Throwable $e, $command): \Throwable;
}
