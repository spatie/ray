<?php

namespace Spatie\Ray\Support;

class RateLimit
{
    /** @var int|null */
    protected $maxCalls;

    /** @var int|null */
    protected $callsPerSeconds;

    /** @var CacheStore */
    protected static $cache;

    private function __construct(?int $maxCalls, ?int $callsPerSeconds)
    {
        $this->maxCalls = $maxCalls;
        $this->callsPerSeconds = $callsPerSeconds;

        self::$cache = new CacheStore();
    }

    public static function disabled(): self
    {
        return new self(null, null);
    }

    public function hit(): self
    {
        $clone = clone $this;

        $clone::$cache->hit();

        return $clone;
    }

    public function max(?int $maxCalls): self
    {
        $clone = clone $this;

        $clone->maxCalls = $maxCalls;

        return $clone;
    }

    public function perSeconds(?int $callsPerSeconds): self
    {
        $clone = clone $this;

        $clone->callsPerSeconds = $callsPerSeconds;

        return $clone;
    }

    public function isMaxReached(): bool
    {
        if ($this->maxCalls === null) {
            return false;
        }

        return self::$cache->count() >= $this->maxCalls;
    }

    public function isPerSecondsReached(): bool
    {
        if ($this->callsPerSeconds === null) {
            return false;
        }

        // @todo
        return false;
    }

    public function clear(): self
    {
        $clone = clone $this;

        $clone->maxCalls = null;
        $clone->callsPerSeconds = null;

        return $clone;
    }
}
