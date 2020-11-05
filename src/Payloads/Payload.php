<?php

namespace Spatie\Timber\Payloads;

use Spatie\Timber\Origin\Origin;
use Spatie\Timber\Origin\DefaultOriginFactory;

abstract class Payload
{
    protected static string $originFactoryClass = DefaultOriginFactory::class;

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
        /** @var \Spatie\Timber\Origin\OriginFactory $originFactory */
        $originFactory = new self::$originFactoryClass;

        return $originFactory->getOrigin();
    }
}
