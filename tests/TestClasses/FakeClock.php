<?php

namespace Spatie\Ray\Tests\TestClasses;

use DateTimeImmutable;
use Spatie\Ray\Support\Clock;

class FakeClock implements Clock
{
    protected $fixedNow;

    public function __construct(DateTimeImmutable $now = null)
    {
        $this->fixedNow = $now ?: new DateTimeImmutable();
    }

    public function freeze(DateTimeImmutable $now = null): void
    {
        $this->fixedNow = $now ?? new DateTimeImmutable();
    }

    public function moveForward(string $modifier): void
    {
        $currentTime = $this->now();

        $modifiedTime = $currentTime->modify("+ {$modifier}");

        $this->freeze($modifiedTime);
    }

    public function now(): DateTimeImmutable
    {
        return $this->fixedNow;
    }
}
