<?php declare(strict_types=1);

namespace Becklyn\Ddd\Commands\Application;

use Becklyn\Ddd\Commands\Domain\Command;
use Becklyn\Ddd\Messages\Domain\Message;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2019-06-05
 */
interface CommandBus
{
    public function dispatch(Command $command, ?Message $correlateWith = null) : void;
}
