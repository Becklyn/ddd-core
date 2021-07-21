<?php

namespace Becklyn\Ddd\Transactions\Application;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-07
 */
interface TransactionManager
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
