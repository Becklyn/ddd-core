<?php

namespace Becklyn\Ddd\Tests\Commands\Application;

use Becklyn\Ddd\Commands\Domain\Command;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-27
 */
interface CommandHandlerTestPostRollbackExecutor
{
    public function execute(\Throwable $e, Command $command): \Throwable;
}
