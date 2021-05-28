<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Support\RateLimiter;

class RateLimiterTest extends TestCase
{
    /** @test */
    public function it_can_initialize_a_disabled_rate_limit(): void
    {
        $rateLimiter = RateLimiter::disabled();

        $this->assertFalse($rateLimiter->isMaxReached());
        $this->assertFalse($rateLimiter->isMaxPerSecondReached());
    }

    /** @test */
    public function it_can_update_the_max_calls(): void
    {
        $rateLimiter = RateLimiter::disabled()
            ->clear()
            ->max(1);

        $this->assertFalse($rateLimiter->isMaxReached());

        $rateLimiter->hit();

        $this->assertTrue($rateLimiter->isMaxReached());
    }

    /** @test */
    public function it_can_update_the_per_second_calls(): void
    {
        $rateLimiter = RateLimiter::disabled()
            ->clear()
            ->perSecond(1);

        $this->assertFalse($rateLimiter->isMaxPerSecondReached());

        $rateLimiter->hit();

        $this->assertTrue($rateLimiter->isMaxPerSecondReached());
    }

    /** @test */
    public function it_can_clear_all_limits(): void
    {
        $rateLimiter = RateLimiter::disabled()
            ->max(1)
            ->perSecond(1)
            ->hit();

        $this->assertTrue($rateLimiter->isMaxReached());
        $this->assertTrue($rateLimiter->isMaxPerSecondReached());

        $rateLimiter->clear();

        $this->assertFalse($rateLimiter->isMaxReached());
        $this->assertFalse($rateLimiter->isMaxPerSecondReached());
    }
}
