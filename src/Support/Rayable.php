<?php

namespace Spatie\Ray\Support;

trait Rayable
{
    public function ray(...$args)
    {
        ray($this, ...$args);

        return $this;
    }

    public function rd(...$args)
    {
        rd($this, ...$args);
    }
}
