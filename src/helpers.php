<?php

use Spatie\LaravelRay\Ray as LaravelRay;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;

if (! function_exists('ray')) {
    /**
     * @param mixed ...$args
     *
     * @return \Spatie\Ray\Ray|LaravelRay
     */
    function ray(...$args)
    {
        if (class_exists(LaravelRay::class)) {
            return app(LaravelRay::class)->send(...$args);
        }

        $settings = SettingsFactory::createFromConfigFile();

        return (new Ray($settings))->send(...$args);
    }
}
