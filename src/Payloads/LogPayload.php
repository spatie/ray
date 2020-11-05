<?php

namespace Spatie\Timber\Payloads;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class LogPayload extends Payload
{
    /** @var mixed */
    protected array $values;

    public static function createForArguments(array $arguments): Payload
    {
        $dumpedArguments = array_map(function ($argument) {
            return self::convertToPrimitive($argument);
        }, $arguments);

        return new static($dumpedArguments);
    }

    protected static function convertToPrimitive($argument)
    {
        if (is_string($argument)) {
            return $argument;
        }

        if (is_int($argument)) {
            return $argument;
        }

        $cloner = new VarCloner();

        $dumper = new HtmlDumper();

        $clonedArgument = $cloner->cloneVar($argument);

        return $dumper->dump($clonedArgument, true);
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
