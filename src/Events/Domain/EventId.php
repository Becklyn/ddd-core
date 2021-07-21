<?php

namespace Becklyn\Ddd\Events\Domain;

use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2019-06-05
 */
final class EventId
{
    private function __construct(private string $id)
    {
        Assert::uuid($id);
    }

    public static function fromString(string $id): EventId
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
