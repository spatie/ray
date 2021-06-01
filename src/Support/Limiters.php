<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Origin\Origin;
use Spatie\Ray\Ray;

class Limiters
{
    /** @var array */
    protected $counters = [];

    public function initialize(Ray $ray, Origin $origin, ?int $limit = null): void
    {
        if (! isset($this->counters[$origin->fingerPrint()])) {
            $this->counters[$origin->fingerPrint()] = [$ray, 0, $limit ?? 0, false];
        }
    }

    public function increment(Origin $origin): array
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return [ray(), -1, 0, false];
        }

        [$ray, $times, $limitValue, $sentMessage] = $this->counters[$name];

        $newTimes = $times + 1;

        $this->counters[$name] = [$ray, $newTimes, $limitValue, $sentMessage];

        return [$ray, $newTimes, $limitValue, $sentMessage];
    }

    public function get(Origin $origin): int
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return 0;
        }

        return $this->counters[$name][1];
    }

    public function getLimit(Origin $origin): int
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return 0;
        }

        return $this->counters[$name][2];
    }

    public function canSendPayload(Origin $origin): bool
    {
        $name = $origin->fingerPrint();

        if (! isset($this->counters[$name])) {
            return true;
        }

        [$ray, $times, $limit, $sentMessage] = $this->counters[$name];

        return $times < $limit || $limit <= 0;
    }

    public function sentRateLimitActiveMessage(?Origin $origin): bool
    {
        if (! $origin) {
            return false;
        }

        if (! isset($this->counters[$origin->fingerPrint()])) {
            return false;
        }

        return $this->counters[$origin->fingerPrint()][3];
    }

    public function clear(): void
    {
        $this->counters = [];
    }

    public function setRay(Origin $origin, Ray $ray): void
    {
        $this->counters[$origin->fingerPrint()][0] = $ray;
    }

    public function setLimit(Origin $origin, int $limit): void
    {
        $this->counters[$origin->fingerPrint()][2] = $limit;
    }

    public function setSentRateLimitActive(Origin $origin): void
    {
        $this->counters[$origin->fingerPrint()][3] = true;
    }
}
