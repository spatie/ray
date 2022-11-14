<?php

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

use Spatie\Ray\Support\RateLimiter;

it('can initialize a disabled rate limit', function () {
    $rateLimiter = RateLimiter::disabled();

    assertFalse($rateLimiter->isMaxReached());
    assertFalse($rateLimiter->isMaxPerSecondReached());
});

it('can update the max calls', function () {
    $rateLimiter = RateLimiter::disabled()
        ->clear()
        ->max(1);

    assertFalse($rateLimiter->isMaxReached());

    $rateLimiter->hit();

    assertTrue($rateLimiter->isMaxReached());
});

it('can update the per second calls', function () {
    $rateLimiter = RateLimiter::disabled()
        ->clear()
        ->perSecond(1);

    assertFalse($rateLimiter->isMaxPerSecondReached());

    $rateLimiter->hit();

    assertTrue($rateLimiter->isMaxPerSecondReached());
});

it('can clear all limits', function () {
    $rateLimiter = RateLimiter::disabled()
        ->max(1)
        ->perSecond(1)
        ->hit();

    assertTrue($rateLimiter->isMaxReached());
    assertTrue($rateLimiter->isMaxPerSecondReached());

    $rateLimiter->clear();

    assertFalse($rateLimiter->isMaxReached());
    assertFalse($rateLimiter->isMaxPerSecondReached());
});
