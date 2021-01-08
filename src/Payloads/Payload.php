<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\Origin\DefaultOriginFactory;
use Spatie\Ray\Origin\Origin;

abstract class Payload
{
    public static string $originFactoryClass = DefaultOriginFactory::class;

    abstract public function getType(): string;

    public ?string $remote_path = null;
    public ?string $local_path = null;

    public function replaceRemotePathWithLocalPath(string $file_path) :string
    {
        if (is_null($this->remote_path) || is_null($this->local_path)) {
            return $file_path;
        }

        return str_replace($this->remote_path, $this->local_path, $file_path);
    }

    public function getContent(): array
    {
        return [];
    }

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

        $origin = $originFactory->getOrigin();

        $origin->file = $this->replaceRemotePathWithLocalPath($origin->file);

        return $origin;
    }
}
