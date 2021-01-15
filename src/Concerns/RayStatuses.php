<?php

namespace Spatie\Ray\Concerns;

/** @mixin \Spatie\Ray\Ray */
trait RayStatuses
{
    public function success(): self
    {
        return $this->status('success');
    }

    public function failure(): self
    {
        return $this->status('failure');
    }
}
