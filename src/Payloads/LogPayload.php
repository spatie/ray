<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\ArgumentConverter;

class LogPayload extends Payload
{
    /** @var array */
    protected $values;

    /** @var array */
    protected $meta = [];

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

        foreach ($values as $key => $value) {
            $this->meta[$key]['clipboard_data'] = $this->getClipboardData($value);
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
            'meta' => $this->meta,
        ];
    }

    protected function getClipboardData(mixed $value): string
    {
        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        return var_export($value, true);
    }
}
