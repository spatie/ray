<?php

namespace Spatie\Ray\Support;

use DateTimeImmutable;

class Clock
{
    /** @var null|\DateTimeImmutable */
    public static $fixedNow = null;

    public static function now(): DateTimeImmutable
    {
        return self::$fixedNow ?? new DateTimeImmutable();
    }

    public static function freeze(DateTimeImmutable $now = null): void
    {
        self::$fixedNow = $now ?? new DateTimeImmutable();
    }

    public static function moveForward(string $modifier)
    {
        $currentTime = self::now();

        $modifiedTime = $currentTime->modify("+ {$modifier}");

        self::freeze($modifiedTime);
    }
}
