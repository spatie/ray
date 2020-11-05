<?php

namespace Spatie\Timber;

use Spatie\Timber\Payloads\Payload;

class Request
{
    protected string $uuid;

    protected array $payloads;

    public function __construct(string $uuid, array $payloads)
    {
        $this->uuid = $uuid;

        $this->payloads = $payloads;
    }

    public function toArray(): array
    {
        $payloads = array_map(function (Payload $payload) {
            return $payload->toArray();
        }, $this->payloads);

        return [
            'uuid' => $this->uuid,
            'payloads' => $payloads,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
