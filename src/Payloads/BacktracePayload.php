<?php

namespace Spatie\Ray\Payloads;

use Spatie\Backtrace\Frame;

class BacktracePayload extends Payload
{
    private array $frames;

    public function __construct(array $frames)
    {
        $this->frames = $frames;
    }

    public function getType(): string
    {
        return 'backtrace';
    }

    public function getContent(): array
    {
        $frames = array_filter(
            $this->frames,
            fn(Frame $frame) => $this->shouldIgnoreFrame($frame)
        );

        return [
            'frames' => array_map(fn(Frame $frame) => [
                'line_number' => $frame->lineNumber,
                'file_name' => $frame->file,
                'method' => $frame->method,
                'class' => $frame->class,
            ], array_values($frames)),
        ];
    }

    private function shouldIgnoreFrame(Frame $frame): bool
    {
        foreach ($this->ignoredNamespaces() as $ignoredNamespace) {
            if (substr($frame->class, 0, strlen($ignoredNamespace)) === $ignoredNamespace) {
                return false;
            }
        }

        return true;
    }

    private function ignoredNamespaces(): array
    {
        return [
            'Spatie\Ray',
            'Spatie\LaravelRay',
        ];
    }
}
