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
        $frames = collect(Backtrace::create()->frames())->reverse();

        $indexOfRay = $frames
            ->search(function (Frame $frame) {
                if ($frame->class === Ray::class) {
                    return true;
                }

                if ($this->startsWith($frame->file, __DIR__)) {
                    return true;
                }

                return false;
            });

        return $frames[$indexOfRay + 1] ?? null;
    }

    public function startsWith(string $hayStack, string $needle): bool
    {
        return strpos($hayStack, $needle) === 0;
    }
}
