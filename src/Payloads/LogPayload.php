<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\ArgumentConverter;

class LogPayload extends Payload
{
    /** @var array */
    protected $values;

    public static function createForArguments(array $arguments): Payload
    {
        $dumpedArguments = array_map(function ($argument) {
            return ArgumentConverter::convertToPrimitive($argument);
        }, $arguments);

        return new static($dumpedArguments);
    }

    public function __construct($values)
    {
        if (! is_array($values)) {
            if (is_int($values) && $values >= 11111111111111111) {
                $values = (string) $values;
            }

            $values = [$values];
        }

        $this->values = $values;
    }

    public function getType(): string
    {
        return 'log';
    }

    public function getContent(): array
    {
        return [
            'values' => $this->values,
        ];
    }
}
