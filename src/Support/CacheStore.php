<?php

namespace Spatie\Ray\Support;

class CacheStore
{
    /** @var array */
    protected $store = [];

    public function hit(): self
    {
        $this->store[] = Clock::now();

        return $this;
    }

    public function clear(): self
    {
        $this->store = [];

        return $this;
    }

    public function count(): int
    {
        return count($this->store);
    }

    public function countLastSecond(): int
    {
        $amount = 0;

        $lastSecond = Clock::now()->modify('-1 second');

        foreach ($this->store as $key => $item) {
            if ($this->isBetween(
                $item->getTimestamp(),
                $lastSecond->getTimestamp(),
                Clock::now()->getTimestamp()
            )
            ) {
                $amount++;
            }
        }

        return $amount;
    }

    protected function isBetween($toCheck, $start, $end): bool
    {
        return $toCheck >= $start && $toCheck <= $end;
    }
}
