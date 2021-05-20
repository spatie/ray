<?php

namespace Spatie\Ray\Support;

class RateLimit
{
    /** @var int|null */
    protected static $maxCalls;

    /** @var int|null */
    protected static $callsPerSeconds;

    /** @var CacheStore */
    protected static $cache;

    private function __construct(?int $maxCalls, ?int $callsPerSeconds)
    {
        self::$maxCalls = $maxCalls;
        self::$callsPerSeconds = $callsPerSeconds;
        self::$cache = static::$cache ?? new CacheStore();
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

    public function perSeconds(?int $callsPerSeconds): self
    {
        $this::$callsPerSeconds = $callsPerSeconds;

        return $this;
    }

    public function isMaxReached(): bool
    {
        if (self::$maxCalls === null) {
            return false;
        }

        return $this->cache()->count() >= self::$maxCalls;
    }

    public function isPerSecondsReached(): bool
    {
        if (self::$callsPerSeconds === null) {
            return false;
        }

        return $this->cache()->countLastSecond() >= self::$callsPerSeconds;
    }

    public function clear(): self
    {
        self::$maxCalls = null;
        self::$callsPerSeconds = null;

        $this->cache()->clear();

        return $this;
    }

    public function cache(): CacheStore
    {
        return self::$cache;
    }
}
