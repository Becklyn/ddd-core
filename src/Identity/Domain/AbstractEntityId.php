<?php declare(strict_types=1);

namespace Becklyn\Ddd\Identity\Domain;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-10-19
 */
abstract class AbstractEntityId implements EntityId
{
    protected function __construct(
        protected string $id,
    ) {
        Assert::uuid($id);
    }

    public static function fromString(string $id) : static
    {
        return new static($id); // @phpstan-ignore-line
    }

    public static function next() : static
    {
        return new static(Uuid::uuid4()->toString()); // @phpstan-ignore-line
    }

    public function asString() : string
    {
        return $this->id;
    }

    public function equals(EntityId $other) : bool
    {
        return $this->id === $other->asString() && static::class === \get_class($other);
    }

    public function entityType() : string
    {
        return \substr(static::class, 0, -2);
    }
}
