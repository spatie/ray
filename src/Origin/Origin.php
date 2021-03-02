<?php

namespace Spatie\Ray\Origin;

class Origin
{
    /**
     * @param string|null $file
     * @param int|null $lineNumber
     */
    public function __construct($file, $lineNumber, $hostname)
    {
        $this->file = $file;

        $this->lineNumber = $lineNumber;

        $this->hostname = $hostname;
    }

    public function toArray(): array
    {
        return [
            'file' => $this->file,
            'line_number' => $this->lineNumber,
            'hostname' => $this->hostname,
        ];
    }

    public function fingerPrint(): string
    {
        return md5(print_r($this->toArray(), true));
    }
}
