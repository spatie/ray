<?php

namespace Spatie\Ray;

use Ramsey\Uuid\Uuid;
use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;
use Spatie\Ray\Payloads\ColorPayload;
use Spatie\Ray\Payloads\HidePayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\NewScreenPayload;
use Spatie\Ray\Payloads\RemovePayload;
use Spatie\Ray\Payloads\SizePayload;
use Spatie\Ray\Payloads\TimePayload;
use Symfony\Component\Stopwatch\Stopwatch;

class Ray
{
    use RayColors;
    use RaySizes;

    protected Client $client;

    public string $uuid;

    /** @var \Symfony\Component\Stopwatch\Stopwatch[] */
    public static array $stopWatches = [];

    public static function create(Client $client = null, string $uuid = null): self
    {
        return new static($client, $uuid);
    }

    public function __construct(Client $client = null, string $uuid = null)
    {
        $this->client = $client ?? new Client();

        $this->uuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function newScreen(string $name = ''): self
    {
        $payload = new NewScreenPayload($name);

        $this->sendRequest([$payload]);

        return $this;
    }

    public function clearScreen()
    {
        return $this->newScreen();
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

    public function remove(): self
    {
        $payload = new RemovePayload();

        $this->sendRequest([$payload]);

        return $this;
    }

    public function hide(): self
    {
        $payload = new HidePayload();

        $this->sendRequest([$payload]);

        return $this;
    }

    public function time(string $stopwatchName = 'default'): self
    {
        if (! isset(static::$stopWatches[$stopwatchName])) {
            $stopwatch = new Stopwatch(true);
            static::$stopWatches[$stopwatchName] = $stopwatch;

            $event = $stopwatch->start($stopwatchName);
            $payload = new TimePayload($stopwatchName, $event);
            $payload->concernsNewTimer();

            return $this->sendRequest([$payload]);
        }

        $stopwatch = static::$stopWatches[$stopwatchName];
        $event = $stopwatch->lap($stopwatchName);
        $payload = new TimePayload($stopwatchName, $event);

        return $this->sendRequest([$payload]);
    }

    public function stopTime(string $stopwatchName = ''): self
    {
        if ($stopwatchName === '') {
            static::$stopWatches = [];

            return $this;
        }

        if (isset(static::$stopWatches[$stopwatchName])) {
            unset(static::$stopWatches[$stopwatchName]);

            return $this;
        }

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
