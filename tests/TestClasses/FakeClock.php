<?php

namespace Spatie\Ray\Tests\TestClasses;

use DateTimeImmutable;
use Spatie\Ray\Support\Clock;

class FakeClock implements Clock
{
    /** @var DateTimeImmutable|null */
    protected $fixedNow;

    public function __construct(DateTimeImmutable $now = null)
    {
        $this->fixedNow = $now;
    }

    public function now(): DateTimeImmutable
    {
        return $this->fixedNow ?? new DateTimeImmutable();
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
}
