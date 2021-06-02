<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Origin\Origin;
use Spatie\Ray\Ray;

class Limiters
{
    /** @var array */
    protected $counters = [];

    public function initialize(Origin $origin, ?int $limit = null): void
    {
        if (! isset($this->counters[$origin->fingerPrint()])) {
            $this->counters[$origin->fingerPrint()] = [0, $limit ?? 0];
        }
    }

    public function increment(Origin $origin): array
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return [-1, 0];
        }

        [$times, $limitValue] = $this->counters[$name];

        $newTimes = $times + 1;

        $this->counters[$name] = [$newTimes, $limitValue];

        return [$newTimes, $limitValue];
    }

    public function canSendPayload(Origin $origin): bool
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return true;
        }

        [$times, $limit] = $this->counters[$name];

        return $times < $limit || $limit <= 0;
    }
}
