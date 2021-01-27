<?php

namespace Spatie\Ray\Payloads;

class ImagePayload extends Payload
{
    /** @var string */
    protected $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        if (file_exists($this->location)) {
            $this->location = 'file://' . $this->location;
        }

        $location = str_replace('"', '', $this->location);

        return [
            'content' => "<img src=\"{$location}\" alt=\"\" />",
            'label' => 'Image',
        ];
    }
}
