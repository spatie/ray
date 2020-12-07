<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\Origin\DefaultOriginFactory;
use Spatie\Ray\Origin\Origin;

abstract class Payload
{
    public static string $originFactoryClass = DefaultOriginFactory::class;

    abstract public function getType(): string;

    abstract public function getContent(): array;

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'content' => $this->getContent(),
            'origin' => $this->getOrigin()->toArray(),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    protected function getOrigin(): Origin
    {
        /** @var \Spatie\Ray\Origin\OriginFactory $originFactory */
        $originFactory = new self::$originFactoryClass;

        return $originFactory->getOrigin();
    }
}
