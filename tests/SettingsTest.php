<?php

namespace Spatie\Ray\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Settings\SettingsFactory;

class SettingsTest extends TestCase
{
    /** @test */
    public function it_can_use_the_default_settings()
    {
        $settings = SettingsFactory::createFromConfigFile();

        $this->assertEquals(23517, $settings->port);
        $this->assertEquals('http://localhost', $settings->host);
    }

    /** @test */
    public function it_can_find_the_settings_file()
    {
        $settings = SettingsFactory::createFromConfigFile(__DIR__ . '/testSettings/subDirectory/subSubDirectory');

        $this->assertEquals(12345, $settings->port);
        $this->assertEquals('http://otherhost', $settings->host);
    }
}
