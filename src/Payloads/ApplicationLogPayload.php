<?php

namespace Spatie\Ray\Payloads;

class ApplicationLogPayload extends Payload
{
    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return 'application_log';
    }

    public function getContent(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
