<?php declare(strict_types=1);

namespace Becklyn\Ddd\Commands\Domain;

use Becklyn\Ddd\Messages\Domain\Message;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2022-08-18
 */
interface Command extends Message
{
    public function id() : CommandId;
}
