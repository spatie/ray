<?php

namespace Spatie\Ray\Payloads;

class JsonPayload extends Payload
{
    /** @var mixed */
    protected $value;

    public function __construct($value)
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
            'value' => json_encode($this->value),
        ];
    }
}
