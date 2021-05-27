<?php

namespace Spatie\Ray\Tests\TestClasses;

use DateTimeImmutable;
use Spatie\Ray\Support\Clock;

class ClockMock implements Clock
{
    private $fixedNow;

    public function __construct(DateTimeImmutable $now = null)
    {
        $this->fixedNow = $now ?: DateTimeImmutable::createFromFormat('U.u', microtime(true));
    }

    public function freeze(DateTimeImmutable $now = null): void
    {
        $this->fixedNow = $now ?? new DateTimeImmutable();
    }

    public function freezeAtSecond(DateTimeImmutable $now = null): void
    {
        $this->fixedNow = $now ?? DateTimeImmutable::createFromFormat('U.u', microtime(true));
    }

    public function now(): DateTimeImmutable
    {
        return $this->fixedNow;
    }
}
