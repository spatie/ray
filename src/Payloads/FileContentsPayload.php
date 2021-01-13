<?php

namespace Spatie\Ray\Payloads;

class FileContentsPayload extends Payload
{
    protected string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        if (!file_exists($this->filename)) {
            $contents = "file not found: '{$this->filename}'";
            $label = null;
        } else {
            $contents = file_get_contents($this->filename);
            $contents = nl2br(htmlentities($contents));
            $label = basename($this->filename);
        }

        return [
            'content' => $contents,
            'label' => $label,
        ];
    }
}
