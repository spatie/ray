<?php

namespace Spatie\Ray\Concerns;

/** @mixin \Spatie\Ray\Ray */
trait RaySizes
{
    public function small(): self
    {
        return $this->color('sm');
    }

    public function large(): self
    {
        return $this->color('lg');
    }
}
