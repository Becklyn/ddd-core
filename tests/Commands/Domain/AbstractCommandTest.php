<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Commands\Domain;

use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2022-08-19
 */
class AbstractCommandTest extends TestCase
{
    public function testCorrelationAndCausationIdsAreSameAsCommandIdForFreshlyCreatedCommands() : void
    {
        $command = new AbstractCommandTestCommand();

        self::assertEquals($command->id(), $command->correlationId());
        self::assertEquals($command->id(), $command->causationId());
    }
}
