<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Ray;

class ExceptionHandler
{
    public function catch(Ray $ray, $callback): Ray
    {
        $this->processCallback($ray, $callback);

        if (! empty(Ray::$caughtExceptions)) {
            throw array_shift(Ray::$caughtExceptions);
        }

        return $ray;
    }

    protected function handleCallable(Ray $ray, $callback, $rethrow = true): Ray
    {
        $paramType = $this->getParamType(new \ReflectionFunction($callback));
        $expectedClasses = $this->getExpectedClasses($paramType);

        if (count($expectedClasses)) {
            $isExpected = false;

            foreach ($expectedClasses as $expectedClass) {
                foreach (Ray::$caughtExceptions as $caughtException) {
                    if (is_a($caughtException, $expectedClass, true)) {
                        $isExpected = true;

                        break 2;
                    }
                }
            }

            if (! $isExpected && ! $rethrow) {
                return $ray;
            }

            if (! $isExpected && $rethrow) {
                throw array_shift(Ray::$caughtExceptions);
            }
        }

        $exception = array_shift(Ray::$caughtExceptions);

        $callbackResult = $callback($exception, $ray);

        return $callbackResult instanceof Ray ? $callbackResult : $ray;
    }

    protected function sendExceptionPayload(Ray $ray): Ray
    {
        $exception = array_shift(Ray::$caughtExceptions);

        return $ray->exception($exception);
    }

    protected function processCallback(Ray $ray, $callback, $rethrow = true): Ray
    {
        if (empty(Ray::$caughtExceptions)) {
            return $ray;
        }

        if (! $callback) {
            return $this->sendExceptionPayload($ray);
        }

        // handle class names
        foreach (Ray::$caughtExceptions as $caughtException) {
            if (is_string($callback) && is_a($caughtException, $callback, true)) {
                return $this->sendExceptionPayload($ray);
            }
        }

        if (is_callable($callback)) {
            return $this->handleCallable($ray, $callback, $rethrow);
        }

        // support arrays of both class names and callables
        if (is_array($callback)) {
            foreach ($callback as $item) {
                $result = $this->processCallback($ray, $item, false);

                // the array item handled the exception
                if (empty(Ray::$caughtExceptions)) {
                    return $result instanceof Ray ? $result : $ray;
                }
            }
        }

        return $ray;
    }

    protected function getExpectedClasses($paramType): array
    {
        if (! $paramType) {
            return [\Exception::class];
        }

        $result = is_a($paramType, '\\ReflectionUnionType') ? $paramType->getTypes() : [$paramType->getName()];

        return array_map(function ($type) {
            if (is_string($type)) {
                return $type;
            }

            return method_exists($type, 'getName') ? $type->getName() : get_class($type);
        }, $result);
    }

    protected function getParamType(\ReflectionFunction $reflection)
    {
        $paramType = null;

        if ($reflection->getNumberOfParameters() > 0) {
            $paramType = $reflection->getParameters()[0]->getType();
        }

        return $paramType;
    }
}
