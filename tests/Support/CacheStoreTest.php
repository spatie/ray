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

    assertSame(3, $this->store->countLastSecond());

    $this->clock->moveForward('1 second');

    assertSame(3, $this->store->countLastSecond());

    $this->clock->moveForward('1 second');

    assertSame(0, $this->store->countLastSecond());
});
