<?php

namespace Spatie\Ray\Support;

use Spatie\Ray\Ray;

class ExceptionHandler
{
    public function catch(Ray $ray, $callback): Ray
    {
        $this->processCallback($ray, $callback);

        if ($ray->caughtException) {
            throw $ray->caughtException;
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
                if (is_a($ray->caughtException, $expectedClass, true)) {
                    $isExpected = true;
                }
            }

            if (! $isExpected && ! $rethrow) {
                return $ray;
            }

            if (! $isExpected && $rethrow) {
                throw $ray->caughtException;
            }
        }

        $callbackResult = $callback($ray->caughtException, $ray);

        $ray->caughtException = null;

        return $callbackResult instanceof Ray ? $callbackResult : $ray;
    }

    protected function sendExceptionPayload(Ray $ray): Ray
    {
        $exception = $ray->caughtException;

        $ray->caughtException = null;

        return $ray->exception($exception);
    }

    protected function processCallback(Ray $ray, $callback, $rethrow = true): Ray
    {
        if (! $ray->caughtException) {
            return $ray;
        }

        if (! $callback) {
            return $this->sendExceptionPayload($ray);
        }

        // handle class names
        if (is_string($callback) && is_a($ray->caughtException, $callback, true)) {
            return $this->sendExceptionPayload($ray);
        }

        if (is_callable($callback)) {
            return $this->handleCallable($ray, $callback, $rethrow);
        }

        // support arrays of both class names and callables
        if (is_array($callback)) {
            foreach($callback as $item) {
                $result = $this->processCallback($ray, $item, false);

                // the array item handled the exception
                if (! $ray->caughtException) {
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
