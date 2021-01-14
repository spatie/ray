<?php

namespace Spatie\Ray\Origin;

class Origin
{
    public ?string $file;

    public ?int $lineNumber;

    public function __construct(?string $file, ?int $lineNumber)
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

    public function fingerPrint(): string
    {
        return md5(print_r($this->toArray(), true));
    }
}
