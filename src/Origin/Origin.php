<?php

namespace Spatie\Timber\Origin;

class Origin
{
    protected string $file;

    protected int $lineNumber;

    public function __construct(string $file, int $lineNumber)
    {
        $this->file = $file;

        $this->lineNumber = $lineNumber;
    }

    public function toArray(): array
    {
        return [
            'file' => $this->file,
            'line_number' => $this->lineNumber,
        ];
    }
}
