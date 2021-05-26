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

        $this->assertFalse($rateLimit->isMaxReached());
        $this->assertFalse($rateLimit->isMaxPerSecondReached());
    }

    /** @test */
    public function it_can_update_the_max_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $rateLimit->clear();
        $rateLimit->max(1);

        $this->assertFalse($rateLimit->isMaxReached());

        $rateLimit->hit();

        $this->assertTrue($rateLimit->isMaxReached());
    }

    /** @test */
    public function it_can_update_the_per_second_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $rateLimit->clear();
        $rateLimit->perSecond(1);

        $this->assertFalse($rateLimit->isMaxPerSecondReached());

        $rateLimit->hit();

        $this->assertTrue($rateLimit->isMaxPerSecondReached());
    }

    /** @test */
    public function it_can_clear_all_limits(): void
    {
        $rateLimit = RateLimit::disabled();

        $rateLimit
            ->max(1)
            ->perSecond(1)
            ->hit();

        $this->assertTrue($rateLimit->isMaxReached());
        $this->assertTrue($rateLimit->isMaxPerSecondReached());

        $rateLimit->clear();

        $this->assertFalse($rateLimit->isMaxReached());
        $this->assertFalse($rateLimit->isMaxPerSecondReached());
    }
}
