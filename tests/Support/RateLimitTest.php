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
        $this->assertFalse($rateLimit->isPerSecondsReached());
    }

    /** @test */
    public function it_can_update_the_max_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $newRateLimit = $rateLimit->max(1);

        $this->assertFalse($rateLimit->isMaxReached()); // immutable check
        $this->assertFalse($newRateLimit->isMaxReached());

        $newRateLimit->hit();

        $this->assertTrue($newRateLimit->isMaxReached());
    }

    /** @test */
    public function it_can_update_the_per_second_calls(): void
    {
        $rateLimit = RateLimit::disabled();

        $newRateLimit = $rateLimit->perSeconds(1);

        $this->assertFalse($rateLimit->isPerSecondsReached()); // immutable check
        $this->assertFalse($newRateLimit->isPerSecondsReached());

        $newRateLimit->hit();

        $this->assertTrue($newRateLimit->isPerSecondsReached());
    }

    /** @test */
    public function it_can_clear_all_limits(): void
    {
        $rateLimit = RateLimit::disabled();

        $rateLimit = $rateLimit->max(1);
        $rateLimit = $rateLimit->perSeconds(1);

        $newRateLimit = $rateLimit->hit();

        $this->assertFalse($rateLimit->isMaxReached()); // immutable check
        $this->assertFalse($rateLimit->isPerSecondsReached()); // immutable check

        //$this->assertTrue($newRateLimit->isMaxReached());
        //$this->assertTrue($newRateLimit->isPerSecondsReached());

        $newRateLimit = $rateLimit->clear();

        //$this->assertTrue($rateLimit->isMaxReached()); // immutable check
        //$this->assertTrue($rateLimit->isPerSecondsReached()); // immutable check

        $this->assertFalse($newRateLimit->isMaxReached());
        $this->assertFalse($newRateLimit->isPerSecondsReached());
    }
}
