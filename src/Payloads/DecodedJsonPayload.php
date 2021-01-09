<?php

namespace Spatie\Ray\Payloads;

class DecodedJsonPayload extends Payload
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return 'json';
    }

    public function getContent(): array
    {
        return [
            'value' => json_decode($this->value),
        ];
    }
}
