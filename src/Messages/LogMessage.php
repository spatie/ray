<?php

namespace Spatie\Timber\Messages;

class LogMessage extends Message
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return 'log';
    }

    public function getContent(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
