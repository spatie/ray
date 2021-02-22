<?php

namespace Spatie\Ray;

use Carbon\Carbon;
use Closure;
use Composer\InstalledVersions;
use Exception;
use Ramsey\Uuid\Uuid;
use Spatie\Backtrace\Backtrace;
use Spatie\LaravelRay\Ray as LaravelRay;
use Spatie\Macroable\Macroable;
use Spatie\Ray\Concerns\RayColors;
use Spatie\Ray\Concerns\RaySizes;
use Spatie\Ray\Origin\DefaultOriginFactory;
use Spatie\Ray\Payloads\CallerPayload;
use Spatie\Ray\Payloads\CarbonPayload;
use Spatie\Ray\Payloads\ClearAllPayload;
use Spatie\Ray\Payloads\ColorPayload;
use Spatie\Ray\Payloads\CreateLockPayload;
use Spatie\Ray\Payloads\CustomPayload;
use Spatie\Ray\Payloads\DecodedJsonPayload;
use Spatie\Ray\Payloads\ExceptionPayload;
use Spatie\Ray\Payloads\FileContentsPayload;
use Spatie\Ray\Payloads\HideAppPayload;
use Spatie\Ray\Payloads\HidePayload;
use Spatie\Ray\Payloads\HtmlPayload;
use Spatie\Ray\Payloads\ImagePayload;
use Spatie\Ray\Payloads\JsonStringPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\MeasurePayload;
use Spatie\Ray\Payloads\NewScreenPayload;
use Spatie\Ray\Payloads\NotifyPayload;
use Spatie\Ray\Payloads\RemovePayload;
use Spatie\Ray\Payloads\ShowAppPayload;
use Spatie\Ray\Payloads\SizePayload;
use Spatie\Ray\Payloads\TablePayload;
use Spatie\Ray\Payloads\TracePayload;
use Spatie\Ray\Payloads\XmlPayload;
use Spatie\Ray\Settings\Settings;
use Spatie\Ray\Settings\SettingsFactory;
use Spatie\Ray\Support\Counters;
use Symfony\Component\Stopwatch\Stopwatch;

class Ray
{
    use RayColors;
    use RaySizes;
    use Macroable;

    /** @var \Spatie\Ray\Settings\Settings */
    public $settings;

    /** @var \Spatie\Ray\Client */
    protected static $client;

    /** @var \Spatie\Ray\Support\Counters */
    public static $counters;

    /** @var string */
    public static $fakeUuid;

    /** @var string */
    public $uuid = '';

    /** @var \Symfony\Component\Stopwatch\Stopwatch[] */
    public static $stopWatches = [];

    /** @var bool|null */
    public static $enabled = null;

    public static function create(Client $client = null, string $uuid = null): self
    {
        $settings = SettingsFactory::createFromConfigFile();

        return new static($settings, $client, $uuid);
    }

    public function __construct(Settings $settings, Client $client = null, string $uuid = null)
    {
        $this->settings = $settings;

        self::$client = $client ?? self::$client ?? new Client($settings->port, $settings->host);

        self::$counters = self::$counters ?? new Counters();

        $this->uuid = $uuid ?? static::$fakeUuid ?? Uuid::uuid4()->toString();

        static::$enabled = static::$enabled ?? $this->settings->enable ?? true;
    }

    public function enable(): self
    {
        static::$enabled = true;

        return $this;
    }

    public function disable(): self
    {
        static::$enabled = false;

        return $this;
    }

    public function enabled(): bool
    {
        return static::$enabled || static::$enabled === null;
    }

    public function disabled(): bool
    {
        return static::$enabled === false;
    }

    public static function useClient(Client $client): void
    {
        self::$client = $client;
    }

    public function newScreen(string $name = ''): self
    {
        $payload = new NewScreenPayload($name);

        return $this->sendRequest($payload);
    }

    public function clearAll(): self
    {
        $payload = new ClearAllPayload();

        return $this->sendRequest($payload);
    }

    public function clearScreen(): self
    {
        return $this->newScreen();
    }

    public function color(string $color): self
    {
        $payload = new ColorPayload($color);

        return $this->sendRequest($payload);
    }

    public function size(string $size): self
    {
        $payload = new SizePayload($size);

        return $this->sendRequest($payload);
    }

    public function remove(): self
    {
        $payload = new RemovePayload();

        return $this->sendRequest($payload);
    }

    public function hide(): self
    {
        $payload = new HidePayload();

        return $this->sendRequest($payload);
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

        if (class_exists(LaravelRay::class) && function_exists('base_path')) {
            $backtrace->applicationPath(base_path());
        }

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

    /**
     * Sends the provided value(s) encoded as a JSON string using json_encode().
     */
    public function toJson(...$values): self
    {
        $payloads = array_map(function ($value) {
            return new JsonStringPayload($value);
        }, $values);

        return $this->sendRequest($payloads);
    }

    /**
     * Sends the provided JSON string(s) decoded using json_decode().
     */
    public function json(string ...$jsons): self
    {
        $payloads = array_map(function ($json) {
            return new DecodedJsonPayload($json);
        }, $jsons);

        return $this->sendRequest($payloads);
    }

    public function file(string $filename): self
    {
        $payload = new FileContentsPayload($filename);

        return $this->sendRequest($payload);
    }

    public function image(string $location): self
    {
        $payload = new ImagePayload($location);

        return $this->sendRequest($payload);
    }

    public function die($status = ''): void
    {
        die($status);
    }

    public function className(object $object): self
    {
        return $this->send(get_class($object));
    }

    public function phpinfo(string ...$properties): self
    {
        if (! count($properties)) {
            return $this->table([
                'PHP version' => phpversion(),
                'Memory limit' => ini_get('memory_limit'),
                'Max file upload size' => ini_get('max_file_uploads'),
                'Max post size' => ini_get('post_max_size'),
                'PHP ini file' => php_ini_loaded_file(),
                "PHP scanned ini file" => php_ini_scanned_files() ,
                'Extensions' => implode(', ', get_loaded_extensions()),
            ], 'PHPInfo');
        }

        $properties = array_flip($properties);

        foreach ($properties as $property => $value) {
            $properties[$property] = ini_get($property);
        }

        return $this->table($properties, 'PHPInfo');
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

    public function carbon(?Carbon $carbon): self
    {
        $payload = new CarbonPayload($carbon);

        $this->sendRequest($payload);

        return $this;
    }

    public function ban(): self
    {
        return $this->send('🕶');
    }

    public function charles(): self
    {
        return $this->send('🎶 🎹 🎷 🕺');
    }

    public function table(array $values, $label = 'Table'): self
    {
        $payload = new TablePayload($values, $label);

        return $this->sendRequest($payload);
    }

    public function count(?string $name = null): self
    {
        $fingerPrint = (new DefaultOriginFactory())->getOrigin()->fingerPrint();

        [$ray, $times] = self::$counters->increment($name ?? $fingerPrint);

        $message = "Called ";

        if ($name) {
            $message .= "`{$name}` ";
        }

        $message .= "{$times} ";

        $message .= $times === 1
            ? 'time'
            : 'times';

        $message .= '.';

        $ray->sendCustom($message, 'Count');

        return $ray;
    }

    public function clearCounters(): self
    {
        self::$counters->clear();

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

    public function html(string $html = ''): self
    {
        $payload = new HtmlPayload($html);

        return $this->sendRequest($payload);
    }

    public function exception(Exception $exception, array $meta = []): self
    {
        $payload = new ExceptionPayload($exception, $meta);

        $this->sendRequest($payload);

        return $this;
    }

    public function xml(string $xml): self
    {
        $payload = new XmlPayload($xml);

        return $this->sendRequest($payload);
    }

    public function raw(...$arguments): self
    {
        if (! count($arguments)) {
            return $this;
        }

        $payloads = array_map(function ($argument) {
            return LogPayload::createForArguments([$argument]);
        }, $arguments);

        return $this->sendRequest($payloads);
    }

    public function send(...$arguments): self
    {
        if (! count($arguments)) {
            return $this;
        }

        if ($this->settings->always_send_raw_values) {
            return $this->raw(...$arguments);
        }

        $payloads = PayloadFactory::createForValues($arguments);

        return $this->sendRequest($payloads);
    }

    public function pass($argument)
    {
        $this->send($argument);

        return $argument;
    }

    public function showApp(): self
    {
        $payload = new ShowAppPayload();

        return $this->sendRequest($payload);
    }

    public function hideApp(): self
    {
        $payload = new HideAppPayload();

        return $this->sendRequest($payload);
    }

    public function sendCustom(string $content, string $label = ''): self
    {
        $customPayload = new CustomPayload($content, $label);

        return $this->sendRequest($customPayload);
    }

    /**
     * @param \Spatie\Ray\Payloads\Payload|\Spatie\Ray\Payloads\Payload[] $payloads
     * @param array $meta
     *
     * @return $this
     * @throws \Exception
     */
    public function sendRequest($payloads, array $meta = []): self
    {
        if (! $this->enabled()) {
            return $this;
        }

        if (! is_array($payloads)) {
            $payloads = [$payloads];
        }

        try {
            if (class_exists(InstalledVersions::class)) {
                $meta['ray_package_version'] = InstalledVersions::getVersion('spatie/ray');
            }
        } catch (Exception $e) {
            // In WordPress this entire package will be rewritten
        }

        $allMeta = array_merge([
            'php_version' => phpversion(),
            'php_version_id' => PHP_VERSION_ID,
        ], $meta);

        foreach ($payloads as $payload) {
            $payload->remotePath = $this->settings->remote_path;
            $payload->localPath = $this->settings->local_path;
        }

        $request = new Request($this->uuid, $payloads, $allMeta);

        self::$client->send($request);

        return $this;
    }

    public static function makePathOsSafe(string $path): string
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
