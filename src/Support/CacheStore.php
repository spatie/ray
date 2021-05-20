<?php

namespace Spatie\Ray\Support;

use Carbon\CarbonImmutable;

class CacheStore
{
    /** @var array */
    protected $store = [];

    public function hit(): self
    {
        $this->store[] = CarbonImmutable::now()->toAtomString();

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
        $amountLastSecond = 0;

        $now = CarbonImmutable::now();
        $lastSecond = $now->subSecond();

        foreach ($this->store as $key => $item) {
            $item = CarbonImmutable::createFromTimeString($item);

            if ($item->isBetween($now, $lastSecond)) {
                $amountLastSecond++;
            }
        }

        return $amountLastSecond;
    }
}
