<?php

namespace Spatie\Ray\Payloads;

use Illuminate\Database\Eloquent\Model;
use Spatie\Ray\ArgumentConvertor;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class LogPayload extends Payload
{
    protected array $values;

    public static function createForArguments(array $arguments): Payload
    {
        $dumpedArguments = array_map(function ($argument) {
            return ArgumentConvertor::convertToPrimitive($argument);
        }, $arguments);

        return new static($dumpedArguments);
    }

    public function __construct($values)
    {
        if (! is_array($values)) {
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
