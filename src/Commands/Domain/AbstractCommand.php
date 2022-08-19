<?php

namespace Becklyn\Ddd\Commands\Domain;

use Becklyn\Ddd\Messages\Domain\MessageTrait;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2022-08-18
 */
class AbstractCommand implements Command
{
    use MessageTrait;

    protected CommandId $id;

    public function __construct()
    {
        $this->id = CommandId::fromString(Uuid::uuid4()->toString());

        $this->correlationId = $this->id->asString();
        $this->correlationType = CommandId::class;

        $this->causationId = $this->correlationId;
        $this->causationType = $this->correlationType;
    }

    public function id(): CommandId
    {
        return $this->id;
    }
}
