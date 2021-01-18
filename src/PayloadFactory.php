<?php

namespace Spatie\Ray;

use Carbon\Carbon;
use Spatie\Ray\Payloads\CarbonPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Payloads\Payload;

class PayloadFactory
{
    protected array $values;

    public static function createForValues(array $arguments): array
    {
        return (new static($arguments))->getPayloads();
    }

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function getPayloads(): array
    {
        return array_map(function($value) {
            return $this->getPayload($value);

        }, $this->values);
    }

    protected function getPayload($value): Payload
    {
        if ($value instanceof Carbon) {
            return new CarbonPayload($value);
        }

        $primitiveValue = ArgumentConverter::convertToPrimitive($value);

        return new LogPayload($primitiveValue);
    }
}
