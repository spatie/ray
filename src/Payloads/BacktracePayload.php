<?php

namespace Spatie\Ray\Payloads;

use Spatie\Backtrace\Frame;

class BacktracePayload extends Payload
{
    protected array $frames;

    public function __construct(array $frames)
    {
        $this->frames = $this->removeRayFrames($frames);
    }

    public function getType(): string
    {
        return 'backtrace';
    }

    public function getContent(): array
    {
        $frames = array_map(fn (Frame $frame) => [
            'file_name' => $frame->file,
            'line_number' => $frame->lineNumber,
            'class' => $frame->class,
            'method' => $frame->method,
        ], $this->frames);

        return compact('frames');
    }

    protected function removeRayFrames(array $frames): array
    {
        $frames = array_filter(
            $frames,
            fn (Frame $frame) => ! $this->isRayFrame($frame)
        );

        return array_values($frames);
    }

    protected function isRayFrame(Frame $frame): bool
    {
        foreach ($this->rayNamespaces() as $rayNamespace) {
            if (substr($frame->class, 0, strlen($rayNamespace)) === $rayNamespace) {
                return true;
            }
        }

        return false;
    }

    protected function rayNamespaces(): array
    {
        return [
            'Spatie\Ray',
            'Spatie\LaravelRay',
        ];
    }
}
