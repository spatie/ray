<?php

namespace Spatie\Ray\Support;

trait Rayable
{
    public function ray(...$args): self
    {
        ray($this, ...$args);

        return $this;
    }

    public function rd(...$args): self
    {
        rd($this, ...$args);

        return $this;
    }
}
