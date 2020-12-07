<?php

namespace Spatie\Ray\Origin;

use Spatie\Ray\Ray;

class DefaultOriginFactory implements OriginFactory
{
    public function getOrigin(): Origin
    {
        $frame = $this->getFrame();

        return new Origin(
            $frame['file'] ?? null,
            $frame['line'] ?? null,
        );
    }

    protected function getFrame(): ?array
    {
        $trace = array_reverse(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        $frameIndex = $this->getIndexOfRayCall($trace);

        if (! $frameIndex) {
            return null;
        }

        return $trace[$frameIndex] ?? null;
    }

    protected function getIndexOfRayCall(array $stackTrace): ?int
    {
        foreach ($stackTrace as $index => $frame) {
            if (($frame['class'] ?? '') === Ray::class) {
                return $index;
            }

            if ($this->startsWith($frame['file'], __DIR__)) {
                return $index;
            }
        }

        return null;
    }

    public function startsWith(string $hayStack, string $needle): bool
    {
        return strpos($hayStack, $needle) === 0;
    }
}
