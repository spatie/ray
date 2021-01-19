<?php

namespace Spatie\Ray;

use Carbon\Carbon;
use Closure;
use Spatie\Ray\Payloads\CarbonPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\Payload;

class PayloadFactory
{
    protected array $values;

    protected static ?Closure $payloadFinder = null;

    public static function createForValues(array $arguments): array
    {
        return (new static($arguments))->getPayloads();
    }

    public static function registerPayloadFinder(callable $callable)
    {
        self::$payloadFinder = $callable;
    }

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function getPayloads(): array
    {
        return array_map(function ($value) {
            return $this->getPayload($value);
        }, $this->values);
    }

    protected function getPayload($value): Payload
    {
        if (self::$payloadFinder) {
            if ($payload = (static::$payloadFinder)($value)) {
                return $payload;
            }
        }

        if ($value instanceof Carbon) {
            return new CarbonPayload($value);
        }

        $primitiveValue = ArgumentConverter::convertToPrimitive($value);

        return new LogPayload($primitiveValue);
    }
}
