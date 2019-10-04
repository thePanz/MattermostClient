<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Model\ModelCollection
 */
class ModelCollectionTest extends TestCase
{
    public function testCreateModelCollection(): void
    {
        $data = [
            ['element0'],
            ['element1'],
        ];

        $models = WrappedModelCollection::createFromArray($data);

        $this->assertCount(2, $models);
        $this->assertSame($data, $models->getItems());

        $this->assertSame(0, $models->key());
        $this->assertSame(['element0'], $models->current());
        $models->next();
        $this->assertTrue($models->valid());

        $this->assertSame(1, $models->key());
        $this->assertSame(['element1'], $models->current());

        $models->next();
        $this->assertFalse($models->valid());

        $models->rewind();
        $this->assertSame(0, $models->key());
        $this->assertTrue($models->valid());
    }
}
