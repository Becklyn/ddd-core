<?php

namespace Becklyn\Ddd\Tests\Commands\Application;

use Becklyn\Ddd\Commands\Application\CommandHandler;
use Becklyn\Ddd\Events\Domain\EventProvider;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-10-19
 */
class DefaultPostRollbackCommandHandlerTestDouble extends CommandHandler
{
    private CommandHandlerTestExecuteExecutor $executeExecutor;

    public function __construct(CommandHandlerTestExecuteExecutor $executeExecutor)
    {
        $this->executeExecutor = $executeExecutor;
    }

    public function handle(CommandHandlerTestCommand $command): void
    {
        $this->handleCommand($command);
    }

    /**
     * @param CommandHandlerTestCommand $command
     */
    protected function execute($command): ?EventProvider
    {
        return $this->executeExecutor->execute($command->getArgument());
    }
}
