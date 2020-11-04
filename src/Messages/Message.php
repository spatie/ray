<?php

namespace Spatie\Timber\Messages;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

abstract class Message
{
    abstract public function getType(): string;

    abstract public function getContent(): array;

    public function getFrame(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'content' => $this->getContent(),
            'frame' => $this->getFrame(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function createFromArgument($argument): Message
    {
        $cloner = new VarCloner();

        $dumper = new CliDumper();

        $clonedArgument = $cloner->cloneVar($argument);

        $string = $dumper->dump($clonedArgument, true);

        $string = rtrim($string, PHP_EOL);

        $string = trim($string, '"');

        return new LogMessage($string);
    }
}
