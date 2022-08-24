<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Messages\Domain;

use Becklyn\Ddd\Commands\Domain\CommandId;
use Becklyn\Ddd\Events\Domain\EventId;
use Becklyn\Ddd\Messages\Domain\CorrelationException;
use Becklyn\Ddd\Messages\Domain\Message;
use Becklyn\Ddd\Messages\Domain\MessageId;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2022-08-19
 */
class MessageTraitTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @param MessageId $correlationId
     *
     * @dataProvider provideCorrelationIds
     */
    public function testCorrelationIdReturnsCorrelationIdOfMessageWithWhichTheFixtureWasCorrelated($correlationId) : void
    {
        $message = $this->givenAMessageWithCorrelationId($correlationId);

        $fixture = new MessageTraitTestDouble();

        $this->whenFixtureIsCorrelatedWithMessage($fixture, $message);

        $this->thenCorrelationIdShouldReturn($fixture, $correlationId);
    }

    protected function provideCorrelationIds() : array
    {
        return [
            [EventId::fromString(Uuid::uuid4()->toString())],
            [CommandId::fromString(Uuid::uuid4()->toString())],
        ];
    }

    private function givenAMessageWithCorrelationId(MessageId $correlationId) : Message
    {
        /** @var ObjectProphecy|Message $message */
        $message = $this->prophesize(Message::class);
        $message->correlationId()->willReturn($correlationId);
        $message->id()->willReturn(EventId::fromString(Uuid::uuid4()->toString()));
        return $message->reveal();
    }

    private function whenFixtureIsCorrelatedWithMessage(MessageTraitTestDouble $fixture, Message $message) : void
    {
        $fixture->correlateWith($message);
    }

    private function thenCorrelationIdShouldReturn(MessageTraitTestDouble $fixture, MessageId $expectedCorrelationId) : void
    {
        self::assertEquals($expectedCorrelationId, $fixture->correlationId());
    }

    public function testCorrelationIdThrowsCorrelationExceptionIfFixtureWasNotYetCorrelated() : void
    {
        $fixture = new MessageTraitTestDouble();
        $this->expectException(CorrelationException::class);
        $fixture->correlationId();
    }

    public function testCorrelationIdThrowsCorrelationExceptionIfCorrelationIdIsOfAnUnsupportedType() : void
    {
        $correlationId = $this->givenAnUnsupportedMessageId();
        $message = $this->givenAMessageWithCorrelationId($correlationId);

        $fixture = new MessageTraitTestDouble();
        $this->whenFixtureIsCorrelatedWithMessage($fixture, $message);

        $this->thenExceptionShouldBeThrownOn(CorrelationException::class, fn() => $fixture->correlationId());
    }

    private function givenAnUnsupportedMessageId() : MessageId
    {
        return new MessageTraitTestUnsupportedId();
    }

    private function thenExceptionShouldBeThrownOn(string $exceptionClass, callable $action) : void
    {
        $this->expectException($exceptionClass);
        $action();
    }

    /**
     * @param MessageId $messageId
     *
     * @dataProvider provideCorrelationIds
     */
    public function testCausationIdReturnsIdOfMessageWithWhichTheFixtureWasCorrelated($messageId) : void
    {
        $message = $this->givenAMessageWithId($messageId);

        $fixture = new MessageTraitTestDouble();

        $this->whenFixtureIsCorrelatedWithMessage($fixture, $message);

        $this->thenCausationIdShouldReturn($fixture, $messageId);
    }

    private function givenAMessageWithId(MessageId $messageId)
    {
        /** @var ObjectProphecy|Message $message */
        $message = $this->prophesize(Message::class);
        $message->id()->willReturn($messageId);
        $message->correlationId()->willReturn(EventId::fromString(Uuid::uuid4()->toString()));
        return $message->reveal();
    }

    private function thenCausationIdShouldReturn(MessageTraitTestDouble $fixture, MessageId $expectedCausationId) : void
    {
        self::assertEquals($expectedCausationId, $fixture->causationId());
    }

    public function testCausationIdThrowsCorrelationExceptionIfFixtureWasNotYetCorrelated() : void
    {
        $fixture = new MessageTraitTestDouble();
        $this->expectException(CorrelationException::class);
        $fixture->causationId();
    }

    public function testCausationIdThrowsCorrelationExceptionIfCausationIdIsOfAnUnsupportedType() : void
    {
        $causationId = $this->givenAnUnsupportedMessageId();
        $message = $this->givenAMessageWithId($causationId);

        $fixture = new MessageTraitTestDouble();
        $this->whenFixtureIsCorrelatedWithMessage($fixture, $message);

        $this->thenExceptionShouldBeThrownOn(CorrelationException::class, fn() => $fixture->causationId());
    }
}
