<?php

namespace Spatie\Ray\Support;

class RateLimit
{
    /** @var int|null */
    protected $maxCalls;

    /** @var int|null */
    protected $callsPerSecond;

    private function __construct(int $maxCalls, int $callsPerSecond)
    {
        $this->maxCalls = $maxCalls;
        $this->callsPerSecond = $callsPerSecond;
    }

    public static function create(): self
    {
        return new self(null, null);
    }

    public function max(?int $maxCalls): self
    {
        $clone = clone $this;

        $clone->maxCalls = $maxCalls;

        return $clone;
    }

    public function perSecond(?int $callsPerSecond): self
    {
        $clone = clone $this;

        $clone->callsPerSecond = $callsPerSecond;

        return $clone;
    }

    public function clear(): self
    {
        $clone = clone $this;

        $clone->maxCalls = null;
        $clone->callsPerSecond = null;

        return $clone;
    }
}
