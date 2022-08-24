<?php declare(strict_types=1);

namespace Becklyn\Ddd\Transactions\Testing;

use Becklyn\Ddd\Transactions\Application\TransactionManager;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-07-25
 *
 * @codeCoverageIgnore
 */
trait TransactionManagerTestTrait
{
    protected TransactionManager|ObjectProphecy $transactionManager;

    protected function initTransactionManagerTestTrait() : void
    {
        $this->transactionManager = $this->prophesize(TransactionManager::class);
    }

    protected function givenTransactionIsBegun() : void
    {
        $this->transactionManager->begin()->shouldBeCalled();
    }

    protected function thenTransactionShouldNotBeRolledBack() : void
    {
        $this->transactionManager->rollback()->shouldNotBeCalled();
    }

    protected function thenTransactionShouldBeCommitted() : void
    {
        $this->transactionManager->commit()->shouldBeCalled();
    }

    protected function thenTransactionShouldBeRolledBack() : void
    {
        $this->transactionManager->rollback()->shouldBeCalled();
    }

    protected function thenTransactionShouldNotBeCommitted() : void
    {
        $this->transactionManager->commit()->shouldNotBeCalled();
    }
}
