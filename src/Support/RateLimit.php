<?php

namespace Spatie\Ray\Support;

class RateLimit
{
    /** @var int|null */
    protected $maxCalls;

    /** @var int|null */
    protected $callsPerSeconds;

    /** @var CacheStore */
    protected $cache;

    private function __construct(?int $maxCalls, ?int $callsPerSeconds)
    {
        $this->maxCalls = $maxCalls;
        $this->callsPerSeconds = $callsPerSeconds;

        $this->cache = new CacheStore();
    }

    public static function disabled(): self
    {
        return new self(null, null);
    }

    public function hit(): self
    {
        $clone = clone $this;

        $clone->cache()->hit();

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

        return $this->cache()->count() >= $this->maxCalls;
    }

    public function isPerSecondsReached(): bool
    {
        if ($this->callsPerSeconds === null) {
            return false;
        }

        return $this->cache()->countLastSecond() >= $this->callsPerSeconds;
    }

    public function clear(): self
    {
        $clone = clone $this;

        $clone->maxCalls = null;
        $clone->callsPerSeconds = null;
        $clone->cache()->clear();

        return $clone;
    }

    public function cache(): CacheStore
    {
        return $this->cache;
    }
}
