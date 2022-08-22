<?php

namespace Becklyn\Ddd\Commands\Application;

use Becklyn\Ddd\Commands\Domain\Command;
use Becklyn\Ddd\Events\Domain\EventProvider;
use Becklyn\Ddd\Events\Domain\EventRegistry;
use Becklyn\Ddd\Transactions\Application\TransactionManager;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-27
 */
abstract class CommandHandler
{
    private TransactionManager $transactionManager;
    protected EventRegistry $eventRegistry;

    /**
     * @required
     */
    public function setTransactionManager(TransactionManager $transactionManager): void
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @required
     */
    public function setEventRegistry(EventRegistry $eventRegistry): void
    {
        $this->eventRegistry = $eventRegistry;
    }

    /**
     * Needs to be called by a public method on the concrete handler which is type hinted to the concrete command class
     */
    protected function handleCommand(Command $command): void
    {
        $this->transactionManager->begin();

        try {
            $aggregateRoot = $this->execute($command);
            if ($aggregateRoot) {
                $this->eventRegistry->dequeueProviderAndRegister($aggregateRoot, $command);
            }
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollback();
            $e = $this->postRollback($e, $command);
            throw $e;
        }
    }

    /**
     * Needs to be implemented by the concrete handler, performing any and all command handling logic
     */
    abstract protected function execute(Command $command): ?EventProvider;

    /**
     * May be overridden by the concrete handler if special processing of exceptions thrown by the try method is required. Must either throw or return any
     * exceptions.
     */
    protected function postRollback(\Throwable $e, Command $command): \Throwable
    {
        return $e;
    }
}
