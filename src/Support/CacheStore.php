<?php

namespace Spatie\Ray\Support;

class CacheStore
{
    /** @var array $cache */
    protected $cache = [];

    public function hit(): self
    {
        $this->cache[] = microtime();

        return $this;
    }

    public function count(): int
    {
        return count($this->cache);
    }

    public function clear(): self
    {
        $this->cache = [];

        return $this;
    }
}
