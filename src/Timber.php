<?php

namespace Spatie\Timber;

use Ramsey\Uuid\Uuid;
use Spatie\Timber\Payloads\ClearScreenPayload;
use Spatie\Timber\Payloads\ColorPayload;
use Spatie\Timber\Payloads\LogPayload;
use Spatie\Timber\Payloads\SizePayload;

class Timber
{
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

    protected function sendRequest(array $payloads): self
    {
        $request = new Request($this->uuid, $payloads);

        $this->client->send($request);

        return $this;
    }
}
