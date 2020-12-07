<?php

namespace Spatie\Ray;

use Ramsey\Uuid\Uuid;
use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Payloads\ClearScreenPayload;
use Spatie\Ray\Payloads\ColorPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\SizePayload;

class Ray
{
    use RayColors;

    protected Client $client;

    public string $uuid;

    public static function create(Client $client = null, string $uuid = null): self
    {
        return new static($client, $uuid);
    }

    public function __construct(Client $client = null, string $uuid = null)
    {
        $this->client = $client ?? new Client();

        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function clearScreen(): self
    {
        $payload = new ClearScreenPayload();

        $this->sendRequest([$payload]);

        return $this;
    }

    public function color(string $color): self
    {
        $payload = new ColorPayload($color);

        $this->sendRequest([$payload]);

        return $this;
    }

    public function size(string $size): self
    {
        $payload = new SizePayload($size);

        $this->sendRequest([$payload]);

        return $this;
    }

    public function send(...$arguments): self
    {
        if (! count($arguments)) {
            return $this;
        }

        $payload = LogPayload::createForArguments($arguments);

        return $this->sendRequest([$payload]);
    }

    public function sendRequest(array $payloads): self
    {
        $request = new Request($this->uuid, $payloads);

        $this->client->send($request);

        return $this;
    }
}
