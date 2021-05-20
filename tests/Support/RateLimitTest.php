<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\RateLimit;

class RateLimitTest extends TestCase
{
    /** @test */
    public function it_can_initialize_a_disabled_rate_limit(): void
    {
        $rateLimit = RateLimit::disabled();

        $this->assertNull($rateLimit->getMax());
        $this->assertNull($rateLimit->getPerSecond());
    }

    /** @test */
    public function it_can_update_the_max_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $newRateLimit = $rateLimit->max(25);

        $this->assertNull($rateLimit->getMax()); // immutable check

        $this->assertSame(25, $newRateLimit->getMax());
    }

    /** @test */
    public function it_can_update_the_per_second_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $newRateLimit = $rateLimit->perSecond(25);

        $this->assertNull($rateLimit->getPerSecond()); // immutable check

        $this->assertSame(25, $newRateLimit->getPerSecond());
    }
}
