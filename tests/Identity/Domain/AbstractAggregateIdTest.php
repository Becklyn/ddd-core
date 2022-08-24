<?php declare(strict_types=1);

namespace Becklyn\Ddd\Tests\Identity\Domain;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2020-04-06
 */
class AbstractAggregateIdTest extends TestCase
{
    public function testFromStringReturnsInstanceWithPassedUuidAsId() : void
    {
        $uuid = Uuid::uuid4()->toString();
        $id = AbstractAggregateTestProxyId::fromString($uuid);
        self::assertEquals($uuid, $id->asString());
    }

    public function testFromStringThrowsExceptionIfNonUuidStringIsPassed() : void
    {
        $this->expectException(\Exception::class);
        AbstractAggregateTestProxyId::fromString('foo');
    }

    public function testNextReturnsInstanceWithUuidAsId() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        Assert::uuid($id->asString());
        self::assertTrue(true);
    }

    public function testEqualsReturnsTrueIfOtherHasSameIdAndIsOfSameClass() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxyId::fromString($id->asString());
        self::assertTrue($id->equals($id2));
    }

    public function testEqualsReturnsFalseIfOtherHasOtherIdAndIsOfSameClass() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxyId::next();
        self::assertFalse($id->equals($id2));
    }

    public function testEqualsReturnsFalseIfOtherHasSameIdAndIsOfDifferentClass() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxy2Id::fromString($id->asString());
        self::assertFalse($id->equals($id2));
    }

    public function testEntityTypeReturnsFullyQualifiedClassNameOfAggregate() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        self::assertEquals(AbstractAggregateTestProxy::class, $id->entityType());
    }

    public function testAggregateTypeReturnsFullyQualifiedClassNameOfAggregate() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        self::assertEquals(AbstractAggregateTestProxy::class, $id->aggregateType());
    }

    public function testAggregateTypeReturnsSamevalueAsEntityType() : void
    {
        $id = AbstractAggregateTestProxyId::next();
        self::assertEquals($id->aggregateType(), $id->entityType());
    }
}
