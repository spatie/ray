<?php

namespace Spatie\Ray\Payloads;

use Exception;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;

class ExceptionPayload extends Payload
{
    /** @var \Throwable */
    protected $exception;

    public function __construct(\Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getType(): string
    {
        return 'exception';
    }

    public function getContent(): array
    {
        Backtrace::createForThrowable($this->exception);

        return [
            'class' => get_class($this->exception),
            'message' => $this->exception->getMessage(),
            'frames' => $this->getFrames(),
        ];
    }

    protected function getFrames(): array
    {
        $frames = Backtrace::createForThrowable($this->exception)->frames();

        return array_map(function (Frame $frame) {
            return [
                'file_name' => $this->replaceRemotePathWithLocalPath($frame->file),
                'line_number' => $frame->lineNumber,
                'class' => $frame->class,
                'method' => $frame->method,
                'vendor_frame' => ! $frame->applicationFrame,
                'snippet' => $frame->getSnippet(12),
            ];
        }, $frames);
    }
}
