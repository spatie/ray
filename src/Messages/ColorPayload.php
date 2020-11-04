<?php

namespace Spatie\Timber\Messages;

class ColorPayload extends Payload
{
    /** @var mixed */
    protected $color;

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function getType(): string
    {
        return 'json';
    }

    public function getContent(): array
    {
        return [
            'color' => json_encode($this->color),
        ];
    }
}
