<?php

namespace Spatie\Timber\Messages;

class SizePayload extends Payload
{
    /** @var mixed */
    protected $size;

    public function __construct(string $size)
    {
        $this->size = $size;
    }

    public function getType(): string
    {
        return 'json';
    }

    public function getContent(): array
    {
        return [
            'color' => json_encode($this->size),
        ];
    }
}
