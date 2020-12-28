<?php

namespace Spatie\Ray\Origin;

use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Spatie\Ray\Ray;

class DefaultOriginFactory implements OriginFactory
{
    public function getOrigin(): Origin
    {
        $frame = $this->getFrame();

        return new Origin(
            $frame ? $frame->file : null,
            $frame ? $frame->lineNumber : null
        );
    }

    protected function getFrame(): ?Frame
    {
        $frames = Backtrace::create()->frames();

        $frames = array_reverse($frames, true);

        $indexOfRay = $this->search(function (Frame $frame) {
            if ($frame->class === Ray::class) {
                return true;
            }

            if ($this->startsWith($frame->file, __DIR__)) {
                return true;
            }

            return false;
        }, $frames);

        return $frames[$indexOfRay + 1] ?? null;
    }

    public function startsWith(string $hayStack, string $needle): bool
    {
        return strpos($hayStack, $needle) === 0;
    }

    protected function search(callable $callable, array $items): ?int
    {
        foreach ($items as $key => $item) {
            if ($callable($item, $key)) {
                return $key;
            }
        }

        return null;
    }
}
