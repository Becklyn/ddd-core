<?php

namespace Becklyn\Ddd\Messages\Domain;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2022-08-18
 */
interface Message
{
    public function id(): MessageId;

    /**
     * @throws CorrelationException
     */
    public function correlationId(): MessageId;

    /**
     * @throws CorrelationException
     */
    public function causationId(): MessageId;

    public function correlateWith(Message $message): void;
}
