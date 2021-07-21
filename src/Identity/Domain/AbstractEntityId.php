<?php

namespace Becklyn\Ddd\Identity\Domain;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-10-19
 */
abstract class AbstractEntityId implements EntityId
{
    protected function __construct(
        protected string $id,
    ) {
        Assert::uuid($id);
    }

    public static function fromString(string $id): static
    {
        return new static($id);
    }

    public static function next(): static
    {
        return new static(Uuid::uuid4());
    }

    public function asString(): string
    {
        return $this->id;
    }

    public function equals(EntityId $other): bool
    {
        return $this->id === $other->asString() && get_class($this) === get_class($other);
    }

    public function entityType(): string
    {
        return substr(get_class($this), 0, -2);
    }
}
