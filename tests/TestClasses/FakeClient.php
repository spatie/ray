<?php

namespace Spatie\Timber\Tests\TestClasses;

use Spatie\Timber\Client;
use Spatie\Timber\Payload;

class FakeClient extends Client
{
    protected array $sentPayloads = [];

    public function send(Payload $payload)
    {
        $this->sentPayloads[] = $payload->toJson();
    }

    public function sentPayloads(): array
    {
        return $this->sentPayloads;
    }
}
