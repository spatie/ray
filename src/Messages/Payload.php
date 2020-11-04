<?php

namespace Spatie\Timber\Messages;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

abstract class Payload
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
            'frame' => $this->getFrame()
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }


}
