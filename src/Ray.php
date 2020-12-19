<?php

namespace Spatie\Ray;

use Closure;
use Ramsey\Uuid\Uuid;
use Spatie\Backtrace\Backtrace;
use Spatie\Macroable\Macroable;
use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;
use Spatie\Ray\Payloads\ColorPayload;
use Spatie\Ray\Payloads\HidePayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\MeasurePayload;
use Spatie\Ray\Payloads\NewScreenPayload;
use Spatie\Ray\Payloads\NotifyPayload;
use Spatie\Ray\Payloads\RemovePayload;
use Spatie\Ray\Payloads\SizePayload;
use Spatie\Ray\Payloads\TracePayload;
use Symfony\Component\Stopwatch\Stopwatch;

class Ray
{
    use RayColors;
    use RaySizes;
    use Macroable;

    protected static Client $client;

    public static string $uuid;

    /** @var \Symfony\Component\Stopwatch\Stopwatch[] */
    public static array $stopWatches = [];

    public static function create(Client $client = null, string $uuid = null): self
    {
        return new static($client, $uuid);
    }

    public function __construct(Client $client = null, string $uuid = null)
    {
        self::$client = $client ?? self::$client ?? new Client();

        static::$uuid = $uuid ?? static::$uuid ?? Uuid::uuid4()->toString();
    }

    public static function useClient(Client $client): void
    {
        self::$client = $client;
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

    /**
     * @param string|callable $stopwatchName
     *
     * @return $this
     */
    public function measure($stopwatchName = 'default'): self
    {
        if ($stopwatchName instanceof Closure) {
            return $this->measureClosure($stopwatchName);
        }

        if (! isset(static::$stopWatches[$stopwatchName])) {
            $stopwatch = new Stopwatch(true);
            static::$stopWatches[$stopwatchName] = $stopwatch;

            $event = $stopwatch->start($stopwatchName);
            $payload = new MeasurePayload($stopwatchName, $event);
            $payload->concernsNewTimer();

            return $this->sendRequest([$payload]);
        }

        $stopwatch = static::$stopWatches[$stopwatchName];
        $event = $stopwatch->lap($stopwatchName);
        $payload = new MeasurePayload($stopwatchName, $event);

        return $this->sendRequest([$payload]);
    }

    public function trace(?Closure $startingFromFrame = null): self
    {
        $backtrace = Backtrace::create();

        if ($startingFromFrame) {
            $backtrace->startingFromFrame($startingFromFrame);
        }

        $payload = new TracePayload($backtrace->frames());

        return $this->sendRequest([$payload]);
    }

    public function caller(): self
    {
        $backtrace = Backtrace::create();

        $payload = (new TracePayload($backtrace->frames()))->limit(1);

        return $this->sendRequest([$payload]);
    }

    protected function measureClosure(Closure $closure): self
    {
        $stopwatch = new Stopwatch(true);

        $stopwatch->start('closure');

        $closure();

        $event = $stopwatch->stop('closure');

        $payload = new MeasurePayload('closure', $event);

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

    public function notify(string $text): self
    {
        $payload = new NotifyPayload($text);

        return $this->sendRequest([$payload]);
    }

    public function die()
    {
        return die();
    }

    public function className(object $object)
    {
        return $this->send(get_class($object));
    }

    public function ban(): self
    {
        $this->send('🕶');

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
        $request = new Request(static::$uuid, $payloads);

        self::$client->send($request);

        return $this;
    }
}
