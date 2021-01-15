<?php

namespace Spatie\Ray\Payloads;

class StatusPayload extends Payload
{
    /** @var mixed */
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return 'color';
    }

    public function getContent(): array
    {
        return [
            'color' => $this->typeColor(),
        ];
    }

    protected function typeColor(): string
    {
        if ($this->type === 'success') {
            return 'green-600 bg-green';
        }

        if ($this->type === 'failure') {
            return 'red-600 bg-red';
        }

        // leading & trailing spaces are required
        return ' transparent ';
    }
}
