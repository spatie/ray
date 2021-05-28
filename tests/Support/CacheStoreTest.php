<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\CacheStore;
use Spatie\Ray\Tests\TestClasses\ClockMock;

class CacheStoreTest extends TestCase
{
    /** @var ClockMock */
    protected $clock;

    /** @var CacheStore */
    protected $store;

    public function setUp(): void
    {
        $this->clock = new ClockMock();
        $this->store = new CacheStore($this->clock);
    }

    /** @test */
    public function it_can_count_per_seconds(): void
    {
        $this->clock->freezeAtSecond();

        $this->store->hit()->hit()->hit();

        $this->assertSame(3, $this->store->countLastSecond());

        $this->clock->freezeAtSecond(
            $this->clock->now()->modify('+1 second')
        );

        $this->assertSame(3, $this->store->countLastSecond());

        $this->clock->freezeAtSecond(
            $this->clock->now()->modify('+1 second')
        );

        $this->assertSame(0, $this->store->countLastSecond());
    }
}
