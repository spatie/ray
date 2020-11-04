<?php

namespace Spatie\Timber\Messages;

class JsonMessage extends Message
{
    /** @var mixed */
    private $value;

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
