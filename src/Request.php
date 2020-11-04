<?php

namespace Spatie\Timber;

use Spatie\Timber\Messages\Payload;

class Request
{
    protected string $uuid;

    protected array $payloads;

    public function __construct(string $uuid, array $payloads)
    {
        $this->uuid = $uuid;

        $this->payloads = $payloads;
    }

    public function toJson(): string
    {
        $messages = array_map(function(Payload $payload) {
            return $payload->toArray();
        }, $this->payloads);

        return json_encode([
            'uuid' => $this->uuid,
            'messages' => $messages,
        ]);
    }
}
