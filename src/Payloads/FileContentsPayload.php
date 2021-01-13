<?php

namespace Spatie\Ray\Payloads;

class FileContentsPayload extends Payload
{
    protected string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        if (!file_exists($this->file)) {
            return [
                'content' => "File not found: '{$this->file}'",
                'label' => 'File',
            ];
        }

        $contents = file_get_contents($this->file);

        return [
            'content' => nl2br(htmlentities($contents)),
            'label' => basename($this->file),
        ];
    }
}
