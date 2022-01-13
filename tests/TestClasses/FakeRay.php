<?php

namespace Spatie\Ray\Tests\TestClasses;

use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RayScreenColors;
use Spatie\Ray\Concerns\RaySizes;

class FakeRay
{
    use RaySizes;
    use RayColors;
    use RayScreenColors;

    /** @var array */
    protected $sizeHistory;

    /** @var array */
    protected $colorHistory;

    /** @var array */
    protected $screenColorHistory;

    public function __construct()
    {
        $this->sizeHistory = [];
        $this->colorHistory = [];
        $this->screenColorHistory = [];
    }

    public function size(string $size): self
    {
        $this->sizeHistory[] = $size;

        return $this;
    }

    public function color(string $color): self
    {
        $this->colorHistory[] = $color;

        return $this;
    }

    public function screenColor(string $color): self
    {
        $this->screenColorHistory[] = $color;

        return $this;
    }

    public function getSizeHistory(): array
    {
        return $this->sizeHistory;
    }

    public function getColorHistory(): array
    {
        return $this->colorHistory;
    }

    public function getScreenColorHistory(): array
    {
        return $this->screenColorHistory;
    }

    public function getLastSize(): string
    {
        return $this->sizeHistory[count($this->sizeHistory) - 1];
    }

    public function getLastColor(): string
    {
        return $this->colorHistory[count($this->colorHistory) - 1];
    }

    public function getLastScreenColor(): string
    {
        return $this->screenColorHistory[count($this->screenColorHistory) - 1];
    }

    public function setPayloads(): array
    {
        return [
            'colors' => $this->colorHistory,
            'sizes' => $this->sizeHistory,
        ];
    }
}
