<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;

use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;

it('can use the default settings', function () {
    $settings = SettingsFactory::createFromConfigFile();

    assertEquals(23517, $settings->port);
    assertEquals('localhost', $settings->host);
});

it('can find the settings file', function () {
    $settings = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    assertEquals(12345, $settings->port);
    assertEquals('http://otherhost', $settings->host);
})->skip(getenv('CI'), 'Test does not run on GitHub actions');

it('can find the settings file more than once', function () {
    $settings1 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    assertEquals(12345, $settings1->port);
    assertEquals('http://otherhost', $settings1->host);

    $settings2 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

    assertEquals(12345, $settings2->port);
    assertEquals('http://otherhost', $settings2->host);
})->skip(getenv('CI'), 'Test does not run on GitHub actions');

it('can create settings from an array', function () {
    $settings = SettingsFactory::createFromArray(['enabled' => false, 'port' => 1234]);

    assertFalse($settings->enabled);
    assertSame(1234, $settings->port);
});
