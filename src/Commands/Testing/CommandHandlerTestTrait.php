<?php

namespace Becklyn\Ddd\Commands\Testing;

use Becklyn\Ddd\Commands\Application\CommandHandler;
use Becklyn\Ddd\Events\Testing\DomainEventTestTrait;
use Becklyn\Ddd\Transactions\Testing\TransactionManagerTestTrait;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-27
 */
trait CommandHandlerTestTrait
{
    use TransactionManagerTestTrait;
    use DomainEventTestTrait;

    /**
     * @var CommandHandler
     */
    protected $fixture;

    protected function initCommandHandlerTestTrait(): void
    {
        $this->initTransactionManagerTestTrait();
        $this->initDomainEventTestTrait();
    }

    protected function commandHandlerPostSetUp(): void
    {
        $this->fixture->setTransactionManager($this->transactionManager->reveal());
        $this->fixture->setEventRegistry($this->eventRegistry->reveal());
    }
}
