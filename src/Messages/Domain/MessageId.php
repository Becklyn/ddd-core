<?php

namespace Becklyn\Ddd\Messages\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2022-08-18
 */
interface MessageId
{
    public function asString(): string;
}