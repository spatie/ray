<?php


namespace Spatie\Ray\Tests\TestClasses;

use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;

class FakeRay
{
    use RaySizes;
    use RayColors;

    protected array $sizeHistory;
    protected array $colorHistory;

    public function __construct()
    {
        $this->sizeHistory = [];
        $this->colorHistory = [];
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

    public function getSizeHistory(): array
    {
        return $this->sizeHistory;
    }

    public function getColorHistory(): array
    {
        return $this->colorHistory;
    }

    public function getLastSize(): string
    {
        return $this->sizeHistory[count($this->sizeHistory) - 1];
    }

    public function getLastColor(): string
    {
        return $this->colorHistory[count($this->colorHistory) - 1];
    }

    public function setPayloads(): array
    {
        return [
            'colors' => $this->colorHistory,
            'sizes' => $this->sizeHistory,
        ];
    }
}
