<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pnz\MattermostClient\Model\ModelCollection;

/**
 * @internal
 */
#[CoversClass(ModelCollection::class)]
final class ModelCollectionTest extends TestCase
{
    public function testCreateModelCollection(): void
    {
        $data = [
            ['element0'],
            ['element1'],
        ];

        $models = MockModelCollection::createFromArray($data);

        $this->assertCount(2, $models);

        $this->assertSame(0, $models->key());
        $this->assertSame(['element0'], $models->current()->data);
        $models->next();
        $this->assertTrue($models->valid());

        $this->assertSame(1, $models->key());
        $this->assertSame(['element1'], $models->current()->data);

        $models->next();
        $this->assertFalse($models->valid());

        $models->rewind();
        $this->assertSame(0, $models->key());
        $this->assertTrue($models->valid());
    }
}
