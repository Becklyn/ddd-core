<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Commands\Application;

use Becklyn\Ddd\Commands\Application\CommandHandler;
use Becklyn\Ddd\Commands\Domain\Command;
use Becklyn\Ddd\Events\Domain\EventProvider;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-27
 */
class CommandHandlerTestDouble extends CommandHandler
{
    public function __construct(
        private CommandHandlerTestExecuteExecutor $executeExecutor,
        private CommandHandlerTestPostRollbackExecutor $postRollbackExecutor,
    ) {
    }

    public function handle(CommandHandlerTestCommand $command) : void
    {
        $this->handleCommand($command);
    }

    /**
     * @param CommandHandlerTestCommand $command
     */
    protected function execute(Command $command) : ?EventProvider
    {
        return $this->executeExecutor->execute($command->getArgument());
    }

    protected function postRollback(\Throwable $e, Command $command) : \Throwable
    {
        return $this->postRollbackExecutor->execute($e, $command);
    }
}
