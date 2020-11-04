<?php

namespace Spatie\Timber\Messages;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class LogPayload extends Payload
{
    /** @var mixed */
    protected array $values;

    public static function createForArguments(array $arguments): Payload
    {
        $cloner = new VarCloner();

        $dumper = new HtmlDumper();

        $dumpedArguments = array_map(function ($argument) use ($dumper, $cloner) {
            $clonedArgument = $cloner->cloneVar($argument);

            $string = $dumper->dump($clonedArgument, true);

            $string = rtrim($string, PHP_EOL);

            return trim($string, '"');
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
