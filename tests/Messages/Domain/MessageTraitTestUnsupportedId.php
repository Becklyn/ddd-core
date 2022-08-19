<?php

namespace Becklyn\Ddd\Tests\Messages\Domain;

use Becklyn\Ddd\Messages\Domain\MessageId;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2022-08-19
 */
class MessageTraitTestUnsupportedId implements MessageId
{
    public function asString(): string
    {
        return "foo";
    }
}
