<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\CacheStore;
use Spatie\Ray\Support\Clock;
use Spatie\Ray\Tests\TestClasses\FakeClock;

class CacheStoreTest extends TestCase
{
    /** @var CacheStore */
    protected $store;

    public function setUp(): void
    {
        $this->store = new CacheStore();
    }

    /** @test */
    public function it_can_count_per_seconds(): void
    {
        Clock::freeze();

        $this->store->hit()->hit()->hit();

        $this->assertSame(3, $this->store->countLastSecond());

        Clock::moveForward('1 second');

        $this->assertSame(3, $this->store->countLastSecond());

        Clock::moveForward('1 second');

        $this->assertSame(0, $this->store->countLastSecond());
    }
}
