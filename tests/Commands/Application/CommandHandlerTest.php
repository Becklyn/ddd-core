<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Commands\Application;

use Becklyn\Ddd\Commands\Domain\Command;
use Becklyn\Ddd\Commands\Testing\CommandHandlerTestTrait;
use Becklyn\Ddd\Events\Domain\EventProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;

class CommandHandlerTest extends TestCase
{
    use ProphecyTrait;
    use CommandHandlerTestTrait;

    private ObjectProphecy|CommandHandlerTestExecuteExecutor $executeExecutor;
    private ObjectProphecy|CommandHandlerTestPostRollbackExecutor $postRollbackExecutor;

    /** @var CommandHandlerTestDouble */
    protected $fixture;

    protected function setUp() : void
    {
        $this->initCommandHandlerTestTrait();
        $this->executeExecutor = $this->prophesize(CommandHandlerTestExecuteExecutor::class);
        $this->postRollbackExecutor = $this->prophesize(CommandHandlerTestPostRollbackExecutor::class);
        $this->fixture = new CommandHandlerTestDouble($this->executeExecutor->reveal(), $this->postRollbackExecutor->reveal());
        $this->commandHandlerPostSetUp();
    }

    public function testExecuteIsExecutedForArgument() : void
    {
        $argument = $this->givenAnArgument();

        $this->whenCommandWithArgumentIsHandled($argument);

        $this->thenExecuteIsExecutedForArgument($argument);
    }

    private function givenAnArgument() : string
    {
        return Uuid::uuid4()->toString();
    }

    private function whenCommandWithArgumentIsHandled(CommandHandlerTestCommand|string $argument) : void
    {
        if (\is_string($argument)) {
            $argument = $this->givenACommandWithArgument($argument);
        }
        $this->fixture->handle($argument);
    }

    private function givenACommandWithArgument(string $argument) : CommandHandlerTestCommand
    {
        return new CommandHandlerTestCommand($argument);
    }

    private function thenExecuteIsExecutedForArgument($argument) : void
    {
        $this->executeExecutor->execute($argument)->shouldHaveBeenCalledtimes(1);
    }

    public function testEventsAreDequeuedCorrelatedAndRegisteredIfExecuteReturnsEventProvider() : void
    {
        $argument = $this->givenAnArgument();
        $command = $this->givenACommandWithArgument($argument);

        $eventProvider = $this->givenExecuteReturnsEventProvider($argument);

        $this->whenCommandWithArgumentIsHandled($command);

        $this->thenEventsAreDequeuedCorrelatedAndRegistered($eventProvider, $command);
    }

    private function givenExecuteReturnsEventProvider($argument) : EventProvider
    {
        /** @var EventProvider $eventProvider */
        $eventProvider = $this->prophesize(EventProvider::class)->reveal();
        $this->executeExecutor->execute($argument)->willReturn($eventProvider);
        return $eventProvider;
    }

    private function thenEventsAreDequeuedCorrelatedAndRegistered(EventProvider $eventProvider, Command $command) : void
    {
        $this->eventRegistry->dequeueProviderAndRegister($eventProvider, $command)->shouldHaveBeenCalledTimes(1);
    }

    public function testEventsAreNotDequeuedAndRegisteredIfExecuteReturnsNull() : void
    {
        $argument = $this->givenAnArgument();
        $this->givenExecuteReturnsNull($argument);

        $this->whenCommandWithArgumentIsHandled($argument);

        $this->thenEventsAreNotDequeuedAndRegistered();
    }

    private function givenExecuteReturnsNull($argument) : void
    {
        $this->executeExecutor->execute($argument)->willReturn(null);
    }

    private function thenEventsAreNotDequeuedAndRegistered() : void
    {
        $this->eventRegistry->dequeueProviderAndRegister(Argument::any(), Argument::any())->shouldNotHaveBeenCalled();
    }

    public function testTransactionIsBegunAndCommitted() : void
    {
        $argument = $this->givenAnArgument();

        $this->whenCommandWithArgumentIsHandled($argument);

        $this->thenTransactionIsBegun();
        $this->thenTransactionIsCommitted();
    }

    private function thenTransactionIsBegun() : void
    {
        $this->transactionManager->begin()->shouldHaveBeenCalledTimes(1);
    }

    private function thenTransactionIsCommitted() : void
    {
        $this->transactionManager->commit()->shouldHaveBeenCalledTimes(1);
    }

    public function testCallingCodeExpectsExceptionIfExecuteThrowsException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $this->givenPostRollbackSimplyPassesExceptionThrough($exception);

        $this->thenCallingCodeExpectsException($exception);

        $this->whenCommandWithArgumentIsHandled($argument);
    }

    private function givenExecuteThrowsException($argument) : \Exception
    {
        $exception = new \Exception('An Exception');
        $this->executeExecutor->execute($argument)->willThrow($exception);
        return $exception;
    }

    private function givenPostRollbackSimplyPassesExceptionThrough($exception) : void
    {
        $this->postRollbackExecutor->execute($exception, Argument::any())->willReturn($exception);
    }

    private function thenCallingCodeExpectsException($exception) : void
    {
        $this->expectExceptionObject($exception);
    }

    public function testCallingCodeExpectsExceptionIfExecuteThrowsExceptionAndHandlerHasInheritedPostRollback() : void
    {
        $this->fixture = new DefaultPostRollbackCommandHandlerTestDouble($this->executeExecutor->reveal());
        $this->commandHandlerPostSetUp();

        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $this->thenCallingCodeExpectsException($exception);
        $this->whenCommandWithArgumentIsHandled($argument);
    }

    public function testNoEventsAreDequeuedAndRegisteredIfExecuteThrowsException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $this->givenPostRollbackSimplyPassesExceptionThrough($exception);

        try {
            $this->whenCommandWithArgumentIsHandled($argument);
        } catch (\Exception $e) {
        }

        $this->thenEventsAreNotDequeuedAndRegistered();
    }

    public function testTransactionIsRolledBackIfExecuteThrowsException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $this->givenPostRollbackSimplyPassesExceptionThrough($exception);

        try {
            $this->whenCommandWithArgumentIsHandled($argument);
        } catch (\Exception $e) {
        }

        $this->thenTransactionIsRolledBack();
    }

    private function thenTransactionIsRolledBack() : void
    {
        $this->transactionManager->rollback()->shouldHaveBeenCalledTimes(1);
    }

    public function testPostRollbackIsExecutedForExceptionIfExecuteThrowsException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $this->givenPostRollbackSimplyPassesExceptionThrough($exception);

        try {
            $this->whenCommandWithArgumentIsHandled($argument);
        } catch (\Exception $e) {
        }

        $this->thenPostRollbackIsExecutedForException($exception);
    }

    private function thenPostRollbackIsExecutedForException($exception) : void
    {
        $this->postRollbackExecutor->execute($exception, Argument::any())->shouldHaveBeenCalledTimes(1);
    }

    public function testCallingCodeExpectsNewExceptionIfPostRollbackThrowsNewException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $newException = $this->givenPostRollbackThrowsNewException($exception);
        self::assertNotSame($exception, $newException);

        $this->thenCallingCodeExpectsException($newException);

        $this->whenCommandWithArgumentIsHandled($argument);
    }

    private function givenPostRollbackThrowsNewException($exception) : \Exception
    {
        $newException = new \Exception('Neeeew exception');
        $this->postRollbackExecutor->execute($exception, Argument::any())->willThrow($newException);
        return $newException;
    }

    public function testCallingCodeExpectsNewExceptionIfPostRollbackReturnsNewException() : void
    {
        $argument = $this->givenAnArgument();
        $exception = $this->givenExecuteThrowsException($argument);
        $newException = $this->givenPostRollbackReturnsNewException($exception);
        self::assertNotSame($exception, $newException);

        $this->thenCallingCodeExpectsException($newException);

        $this->whenCommandWithArgumentIsHandled($argument);
    }

    private function givenPostRollbackReturnsNewException($exception) : \Exception
    {
        $newException = new \Exception('Neeeew exception');
        $this->postRollbackExecutor->execute($exception, Argument::any())->willReturn($newException);
        return $newException;
    }
}
