<?php

namespace Becklyn\Ddd\Tests\Events\Domain;

use Becklyn\Ddd\Events\Domain\AggregateEventStream;
use Becklyn\Ddd\Tests\Identity\Domain\AbstractAggregateTestProxyId;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 * @since  2020-04-06
 */
class AggregateEventStreamTest extends TestCase
{
    public function testAggregateIdReturnsAggregateIdPassedToConstructor(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $stream = new AggregateEventStream($id, Collection::make());
        $this->assertSame($id, $stream->aggregateId());
    }

    public function testEventsReturnsEventsCollectionPassedToConstructor(): void
    {
        $collection = Collection::make();
        $stream = new AggregateEventStream(AbstractAggregateTestProxyId::next(), $collection);
        $this->assertSame($collection, $stream->events());
    }
}
