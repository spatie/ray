<?php

namespace Spatie\Ray;

use Closure;
use Composer\InstalledVersions;
use Ramsey\Uuid\Uuid;
use Spatie\Backtrace\Backtrace;
use Spatie\Macroable\Macroable;
use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;
use Spatie\Ray\Payloads\CallerPayload;
use Spatie\Ray\Payloads\ColorPayload;
use Spatie\Ray\Payloads\CreateLockPayload;
use Spatie\Ray\Payloads\CustomPayload;
use Spatie\Ray\Payloads\HidePayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\MeasurePayload;
use Spatie\Ray\Payloads\NewScreenPayload;
use Spatie\Ray\Payloads\NotifyPayload;
use Spatie\Ray\Payloads\RemovePayload;
use Spatie\Ray\Payloads\SizePayload;
use Spatie\Ray\Payloads\TracePayload;
use Spatie\Ray\Settings\Settings;
use Symfony\Component\Stopwatch\Stopwatch;

class Ray
{
    use RayColors;
    use RaySizes;
    use Macroable;

    public Settings $settings;

    protected static Client $client;

    public static string $fakeUuid;

    public string $uuid = '';

    /** @var \Symfony\Component\Stopwatch\Stopwatch[] */
    public static array $stopWatches = [];

    public static function create(Client $client = null, string $uuid = null): self
    {
        $settings = Settings::load();

        return new static($settings, $client, $uuid);
    }

    public function __construct(Settings $settings, Client $client = null, string $uuid = null)
    {
        $this->settings = $settings;

        self::$client = $client ?? self::$client ?? new Client($settings->port, $settings->host);

        $this->uuid = $uuid ?? static::$fakeUuid ?? Uuid::uuid4()->toString();
    }

    public static function useClient(Client $client): void
    {
        self::$client = $client;
    }

    public function newScreen(string $name = ''): self
    {
        $payload = new NewScreenPayload($name);

        $this->sendRequest($payload);

        return $this;
    }

    public function clearScreen()
    {
        return $this->newScreen();
    }

    public function color(string $color): self
    {
        $payload = new ColorPayload($color);

        $this->sendRequest($payload);

        return $this;
    }

    public function size(string $size): self
    {
        $payload = new SizePayload($size);

        $this->sendRequest($payload);

        return $this;
    }

    public function remove(): self
    {
        $payload = new RemovePayload();

        $this->sendRequest($payload);

        return $this;
    }

    public function hide(): self
    {
        $payload = new HidePayload();

        $this->sendRequest($payload);

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

            return $this->sendRequest($payload);
        }

        $stopwatch = static::$stopWatches[$stopwatchName];
        $event = $stopwatch->lap($stopwatchName);
        $payload = new MeasurePayload($stopwatchName, $event);

        return $this->sendRequest($payload);
    }

    public function trace(?Closure $startingFromFrame = null): self
    {
        $backtrace = Backtrace::create();

        if ($startingFromFrame) {
            $backtrace->startingFromFrame($startingFromFrame);
        }

        $payload = new TracePayload($backtrace->frames());

        return $this->sendRequest($payload);
    }

    public function backtrace(?Closure $startingFromFrame = null): self
    {
        return $this->trace($startingFromFrame);
    }

    public function caller(): self
    {
        $backtrace = Backtrace::create();

        $payload = (new CallerPayload($backtrace->frames()));

        return $this->sendRequest($payload);
    }

    protected function measureClosure(Closure $closure): self
    {
        $stopwatch = new Stopwatch(true);

        $stopwatch->start('closure');

        $closure();

        $event = $stopwatch->stop('closure');

        $payload = new MeasurePayload('closure', $event);

        return $this->sendRequest($payload);
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

        return $this->sendRequest($payload);
    }

    public function die($status = '')
    {
        die($status);
    }

    public function className(object $object): self
    {
        return $this->send(get_class($object));
    }

    public function showWhen($boolOrCallable): self
    {
        if (is_callable($boolOrCallable)) {
            $boolOrCallable = (bool)$boolOrCallable();
        }

        if (! $boolOrCallable) {
            $this->remove();
        }

        return $this;
    }

    public function showIf($boolOrCallable): self
    {
        return $this->showWhen($boolOrCallable);
    }

    public function removeWhen($boolOrCallable): self
    {
        if (is_callable($boolOrCallable)) {
            $boolOrCallable = (bool)$boolOrCallable();
        }

        if ($boolOrCallable) {
            $this->remove();
        }

        return $this;
    }

    public function removeIf($boolOrCallable): self
    {
        return $this->removeWhen($boolOrCallable);
    }

    public function ban(): self
    {
        $this->send('🕶');

        return $this;
    }

    public function charles(): self
    {
        $this->send('🎶 🎹 🎷 🕺');

        return $this;
    }

    public function pause(): self
    {
        $lockName = md5(time());

        $payload = new CreateLockPayload($lockName);

        $this->sendRequest($payload);

        do {
            sleep(1);
        } while (self::$client->lockExists($lockName));

        return $this;
    }

    public function send(...$arguments): self
    {
        if (! count($arguments)) {
            return $this;
        }

        $payload = LogPayload::createForArguments($arguments);

        return $this->sendRequest($payload);
    }

    public function sendCustom(string $content, string $label = ''): self
    {
        $customPayload = new CustomPayload($content, $label);

        return $this->sendRequest($customPayload);
    }

    /**
     * @param \Spatie\Ray\Payloads\Payload|\Spatie\Ray\Payloads\Payload[]$payloads
     * @param array $meta
     *
     * @return $this
     * @throws \Exception
     */
    public function sendRequest($payloads, array $meta = []): self
    {
        if (! is_array($payloads)) {
            $payloads = [$payloads];
        }

        if (class_exists(InstalledVersions::class)) {
            $meta['ray_package_version'] = InstalledVersions::getVersion('spatie/ray');
        }

        $allMeta = array_merge([
            'php_version' => phpversion(),
            'php_version_id' => PHP_VERSION_ID,
        ], $meta);

        $request = new Request($this->uuid, $payloads, $allMeta);

        self::$client->send($request);

        return $this;
    }

    public static function makePathOsSafe(string $path): string
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
