<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\CacheStore;
use Spatie\TestTime\TestTime;

class CacheStoreTest extends TestCase
{
    /** @test */
    public function it_can_count_per_seconds(): void
    {
        $store = new CacheStore();

        $store->hit()->hit()->hit();

        $this->assertSame(3, $store->countLastSecond());

        TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:01');

        $this->assertSame(3, $store->countLastSecond());

        TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:02');

        $this->assertSame(0, $store->countLastSecond());
    }
}
