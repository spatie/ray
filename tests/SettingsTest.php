<?php


use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;

it('can use the default settings', function () {
    $settings = SettingsFactory::createFromConfigFile();

    expect($settings->port)->toEqual(23517);
    expect($settings->host)->toEqual('localhost');
});

it('can find the settings file', function () {
    $settings = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    expect($settings->port)->toEqual(12345);
    expect($settings->host)->toEqual('http://otherhost');
})->skip(getenv('CI'), 'Test does not run on GitHub actions');

it('can find the settings file more than once', function () {
    $settings1 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    expect($settings1->port)->toEqual(12345);
    expect($settings1->host)->toEqual('http://otherhost');

    $settings2 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    expect($settings2->port)->toEqual(12345);
    expect($settings2->host)->toEqual('http://otherhost');
})->skip(getenv('CI'), 'Test does not run on GitHub actions');

it('can create settings from an array', function () {
    $settings = SettingsFactory::createFromArray(['enabled' => false, 'port' => 1234]);

    expect($settings->enabled)->toBeFalse();
    expect($settings->port)->toBe(1234);
});
