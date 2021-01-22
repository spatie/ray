<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\Origin\DefaultOriginFactory;
use Spatie\Ray\Origin\Origin;

abstract class Payload
{
    public static string $originFactoryClass = DefaultOriginFactory::class;

    abstract public function getType(): string;

    public ?string $remotePath = null;
    public ?string $localPath = null;

    public function replaceRemotePathWithLocalPath(string $filePath): string
    {
        if (is_null($this->remotePath) || is_null($this->localPath)) {
            return $filePath;
        }

        return substr_replace($filePath, $this->localPath, 0, strlen($this->remotePath));
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
