<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Origin\Origin;

class Limiters
{
    /** @var array */
    protected $counters = [];

    public function initialize(Origin $origin, int $limit): void
    {
        if (! isset($this->counters[$origin->fingerPrint()])) {
            $this->counters[$origin->fingerPrint()] = [0, $limit];
        }
    }

    public function increment(Origin $origin): array
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return [-1, 0];
        }

        [$times, $limit] = $this->counters[$name];

        $newTimes = $times + 1;

        $this->counters[$name] = [$newTimes, $limit];

        return [$newTimes, $limit];
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
