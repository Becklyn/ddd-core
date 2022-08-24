<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Commands\Application;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-27
 */
interface CommandHandlerTestExecuteExecutor
{
    public function execute($argument);
}
