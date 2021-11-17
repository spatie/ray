<?php

namespace Spatie\Ray\Payloads;

class ApplicationLogPayload extends Payload
{
    /** @var string */
    protected $value;

    /** @var array */
    protected $context;

    public function __construct(string $value, array $context = [])
    {
        $this->value = $value;
        $this->context = $context;
    }

    public function getType(): string
    {
        return 'application_log';
    }

    public function getContent(): array
    {
        return [
            'value' => $this->value,
            'context' => $this->context,
        ];
    }
}
