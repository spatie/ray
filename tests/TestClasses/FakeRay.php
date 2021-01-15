<?php


namespace Spatie\Ray\Tests\TestClasses;

use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;
use Spatie\Ray\Concerns\RayStatuses;

class FakeRay
{
    use RaySizes;
    use RayColors;
    use RayStatuses;

    protected array $sizeHistory;
    protected array $colorHistory;
    protected array $statusHistory;

    public function __construct()
    {
        $this->colorHistory = [];
        $this->sizeHistory = [];
        $this->statusHistory = [];
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

    public function status(string $status): self
    {
        $this->statusHistory[] = $status;

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

    public function getStatusHistory(): array
    {
        return $this->statusHistory;
    }

    public function getLastSize(): string
    {
        return $this->sizeHistory[count($this->sizeHistory) - 1];
    }

    public function getLastColor(): string
    {
        return $this->colorHistory[count($this->colorHistory) - 1];
    }

    public function getLastStatus(): string
    {
        return $this->statusHistory[count($this->statusHistory) - 1];
    }

    public function setPayloads(): array
    {
        return [
            'colors' => $this->colorHistory,
            'sizes' => $this->sizeHistory,
            'statuses' => $this->statusHistory,
        ];
    }
}
