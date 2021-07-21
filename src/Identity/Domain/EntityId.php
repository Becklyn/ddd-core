<?php

namespace Becklyn\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-10-19
 */
interface EntityId
{
    public function asString(): string;

    public function equals(EntityId $other): bool;

    public function entityType(): string;
}
