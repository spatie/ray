<?php

namespace Spatie\Ray\Concerns;

/** @mixin \Spatie\Ray\Ray */
trait RayColors
{
    public function green(): self
    {
        return $this->color('green');
    }

    public function orange(): self
    {
        return $this->color('orange');
    }

    public function purple(): self
    {
        return $this->color('purple');
    }

    public function gray(): self
    {
        return $this->color('grey');
    }

    public function red(): self
    {
        return $this->color('red');
    }
}
