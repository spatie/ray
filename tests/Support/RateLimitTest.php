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
        $rateLimit = RateLimit::disabled()
            ->max(1);

        $this->assertFalse($rateLimit->isMaxReached());

        $rateLimit->hit();

        $this->assertTrue($rateLimit->isMaxReached());
    }

    /** @test */
    public function it_can_update_the_per_second_calls(): void
    {
        $rateLimit = RateLimit::disabled()
            ->perSeconds(1);

        $this->assertFalse($rateLimit->isPerSecondsReached());

        $rateLimit->hit();

        $this->assertTrue($rateLimit->isPerSecondsReached());
    }

    /** @test */
    public function it_can_clear_all_limits(): void
    {
        $rateLimit = RateLimit::disabled()
            ->max(1)
            ->perSeconds(1);

        $rateLimit->hit();

        $this->assertTrue($rateLimit->isMaxReached());
        $this->assertTrue($rateLimit->isPerSecondsReached());

        $rateLimit->clear();

        $this->assertFalse($rateLimit->isMaxReached());
        $this->assertFalse($rateLimit->isPerSecondsReached());
    }
}
