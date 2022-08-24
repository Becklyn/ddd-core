<?php declare(strict_types=1);

namespace Becklyn\Ddd\Commands\Domain;

use Becklyn\Ddd\Messages\Domain\MessageId;
use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2022-08-18
 */
final class CommandId implements MessageId
{
    private function __construct(private string $id)
    {
        Assert::uuid($id);
    }

    public static function fromString(string $id) : self
    {
        return new self($id);
    }

    public function asString() : string
    {
        return $this->id;
    }
}
