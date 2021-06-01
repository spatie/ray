<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Ray;

class Limiters
{
    /** @var array */
    protected $counters = [];

    public function initialize(string $name, ?int $limit = null): void
    {
        if (! isset($this->counters[$name])) {
            $this->counters[$name] = [ray(), 0, $limit ?? 0];
        }
    }

    public function increment(string $name): array
    {
        if (! isset($this->counters[$name])) {
            return [ray(), -1, 0];
        }

        [$ray, $times, $limitValue] = $this->counters[$name];

        $newTimes = $times + 1;

        $this->counters[$name] = [$ray, $newTimes, $limitValue];

        return [$ray, $newTimes, $limitValue];
    }

    public function get(string $name): int
    {
        if (! isset($this->counters[$name])) {
            return 0;
        }

        return $this->counters[$name][1];
    }

    public function getLimit(string $name): int
    {
        if (! isset($this->counters[$name])) {
            return 0;
        }

        return $this->counters[$name][2];
    }

    public function canSendPayload(string $name): bool
    {
        if (! isset($this->counters[$name])) {
            return true;
        }

        [$ray, $times, $limit] = $this->counters[$name];

        return $times < $limit || $limit <= 0;
    }

    public function clear(): void
    {
        $this->counters = [];
    }

    public function setRay(string $name, Ray $ray): void
    {
        $this->counters[$name][0] = $ray;
    }

    public function setLimit(string $name, int $limit): void
    {
        $this->counters[$name][2] = $limit;
    }
}
