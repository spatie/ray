<?php

namespace Spatie\Ray\Support;

use ReflectionClass;
use Spatie\Ray\Ray;

class Invador
{
    public $obj;
    public $reflected;
    public $ray;

    public function __construct(object $obj, Ray $ray)
    {
        $this->obj = $obj;
        $this->reflected = new ReflectionClass($obj);
        $this->ray = $ray;
    }

    public function __get(string $name): Ray
    {
        $property = $this->reflected->getProperty($name);

        if (PHP_VERSION_ID < 80100) {
            $property->setAccessible(true);
        }

        $value = $property->getValue($this->obj);

        return $this->ray->send($value);
    }

    public function __call(string $name, array $params = []): Ray
    {
        $method = $this->reflected->getMethod($name);

        if (PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }

        $result = $method->invoke($this->obj, ...$params);

        return $this->ray->send($result);
    }
}
