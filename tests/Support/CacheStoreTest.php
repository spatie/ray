<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\CacheStore;
use Spatie\Ray\Tests\TestClasses\FakeClock;

class CacheStoreTest extends TestCase
{
    /** @var FakeClock */
    protected $clock;

    /** @var CacheStore */
    protected $store;

    public function setUp(): void
    {
        $this->clock = new FakeClock();
        $this->store = new CacheStore($this->clock);
    }

    /** @test */
    public function it_can_count_per_seconds(): void
    {
        $this->clock->freeze();

        $this->store->hit()->hit()->hit();

        $this->assertSame(3, $this->store->countLastSecond());

        $this->clock->moveForward('1 second');

        $this->assertSame(3, $this->store->countLastSecond());

        $this->clock->moveForward('1 second');

        $this->assertSame(0, $this->store->countLastSecond());
    }
}
