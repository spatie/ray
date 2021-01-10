<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\ArgumentConverter;

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
        $decodedJson = json_decode($this->value, true);

        return [
            'value' => ArgumentConverter::convertToPrimitive($decodedJson),
        ];
    }
}
