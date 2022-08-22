<?php

namespace Becklyn\Ddd\Tests\Commands\Application;

use Becklyn\Ddd\Commands\Domain\AbstractCommand;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-27
 */
class CommandHandlerTestCommand extends AbstractCommand
{
    private $argument;

    public function __construct($argument)
    {
        parent::__construct();
        $this->argument = $argument;
    }

    public function getArgument()
    {
        return $this->argument;
    }
}
