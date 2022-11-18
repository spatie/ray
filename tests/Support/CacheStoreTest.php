<?php

use function PHPUnit\Framework\assertSame;

use Spatie\Ray\Support\CacheStore;
use Spatie\Ray\Tests\TestClasses\FakeClock;

beforeEach(function () {
    $this->clock = new FakeClock();
    $this->store = new CacheStore($this->clock);
});

it('can count per seconds', function () {
    $this->clock->freeze();

    $this->store->hit()->hit()->hit();

    expect($this->store->countLastSecond())->toBe(3);

    $this->clock->moveForward('1 second');

    expect($this->store->countLastSecond())->toBe(3);

    $this->clock->moveForward('1 second');

    expect($this->store->countLastSecond())->toBe(0);
});
