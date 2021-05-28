<?php

namespace Spatie\Ray\Support;

class RateLimiter
{
    /** @var int|null */
    protected static $maxCalls;

    /** @var int|null */
    protected static $maxPerSecond;

    /** @var CacheStore */
    protected static $cache;

    /** @var bool */
    protected static $notified;

    private function __construct(?int $maxCalls, ?int $maxPerSecond)
    {
        self::$maxCalls = $maxCalls;
        self::$maxPerSecond = $maxPerSecond;
        self::$cache = static::$cache ?? new CacheStore(new SystemClock());
    }

    public static function disabled(): self
    {
        return new self(null, null);
    }

    public function hit(): self
    {
        $this->cache()->hit();

        return $this;
    }

    public function max(?int $maxCalls): self
    {
        $this::$maxCalls = $maxCalls;

        return $this;
    }

    public function perSecond(?int $callsPerSecond): self
    {
        $this::$maxPerSecond = $callsPerSecond;

        return $this;
    }

    public function isMaxReached(): bool
    {
        if (self::$maxCalls === null) {
            return false;
        }

        $reached = $this->cache()->count() >= self::$maxCalls;

        if ($reached === false) {
            self::$notified = false;
        }

        return $reached;
    }

    public function isMaxPerSecondReached(): bool
    {
        if (self::$maxPerSecond === null) {
            return false;
        }

        $reached = $this->cache()->countLastSecond() >= self::$maxPerSecond;

        if ($reached === false) {
            self::$notified = false;
        }

        return $reached;
    }

    public function clear(): self
    {
        self::$maxCalls = null;
        self::$maxPerSecond = null;

        $this->cache()->clear();

        return $this;
    }

    public function isNotified(): bool
    {
        return self::$notified;
    }

    public function notify(): void
    {
        self::$notified = true;
    }

    public function cache(): CacheStore
    {
        return self::$cache;
    }
}
