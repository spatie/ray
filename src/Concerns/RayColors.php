<?php

namespace Spatie\Ray\Concerns;

/** @mixin \Spatie\Ray\Ray */
trait RayColors
{
    public function green(): self
    {
        return $this->color('green');
    }

    public function red(): self
    {
        return $this->color('red');
    }

    public function orange(): self
    {
        return $this->color('orange');
    }

    public function yellow(): self
    {
        return $this->color('yellow');
    }

    public function purple(): self
    {
        return $this->color('purple');
    }
}
