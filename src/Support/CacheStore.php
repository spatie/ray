<?php

namespace Spatie\Ray\Support;

use Carbon\CarbonImmutable;
use DateTimeImmutable;

class CacheStore
{
    /** @var array */
    protected $store = [];

    /** @var Clock */
    protected $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function hit(): self
    {
        $this->store[] = $this->clock->now()::createFromFormat('U.u', microtime(true));

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

        $lastSecond = $this->clock->now()->modify('-1 second');

        $nowAsInt = strtotime($this->clock->now()->format('YmdHisu'));
        $lastSecondAsInt = strtotime($lastSecond->format('YmdHisu'));

        foreach ($this->store as $key => $item) {
            $itemAsInt = strtotime($item->format('YmdHisu'));

            if ($this->isBetween($itemAsInt, $lastSecondAsInt, $nowAsInt)) {
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
