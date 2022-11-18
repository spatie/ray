<?php


use Spatie\Ray\Support\RateLimiter;

it('can initialize a disabled rate limit', function () {
    $rateLimiter = RateLimiter::disabled();

    expect($rateLimiter->isMaxReached())->toBeFalse();
    expect($rateLimiter->isMaxPerSecondReached())->toBeFalse();
});

it('can update the max calls', function () {
    $rateLimiter = RateLimiter::disabled()
        ->clear()
        ->max(1);

    expect($rateLimiter->isMaxReached())->toBeFalse();

    $rateLimiter->hit();

    expect($rateLimiter->isMaxReached())->toBeTrue();
});

it('can update the per second calls', function () {
    $rateLimiter = RateLimiter::disabled()
        ->clear()
        ->perSecond(1);

    expect($rateLimiter->isMaxPerSecondReached())->toBeFalse();

    $rateLimiter->hit();

    expect($rateLimiter->isMaxPerSecondReached())->toBeTrue();
});

it('can clear all limits', function () {
    $rateLimiter = RateLimiter::disabled()
        ->max(1)
        ->perSecond(1)
        ->hit();

    expect($rateLimiter->isMaxReached())->toBeTrue();
    expect($rateLimiter->isMaxPerSecondReached())->toBeTrue();

    $rateLimiter->clear();

    expect($rateLimiter->isMaxReached())->toBeFalse();
    expect($rateLimiter->isMaxPerSecondReached())->toBeFalse();
});
