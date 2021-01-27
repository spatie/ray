<?php

namespace Spatie\Ray\Origin;

class Origin
{
    /**
     * @param string|null $file
     * @param int|null $lineNumber
     */
    public function __construct($file, $lineNumber)
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
