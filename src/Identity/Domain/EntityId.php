<?php declare(strict_types=1);

namespace Becklyn\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-10-19
 */
interface EntityId
{
    public function asString() : string;

    public function equals(self $other) : bool;

    public function entityType() : string;
}
