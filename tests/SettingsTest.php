<?php

namespace Spatie\Ray\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;

class SettingsTest extends TestCase
{
    /** @test */
    public function it_can_use_the_default_settings()
    {
        $settings = SettingsFactory::createFromConfigFile();

        $this->assertEquals(23517, $settings->port);
        $this->assertEquals('localhost', $settings->host);
    }

    /** @test */
    public function it_can_find_the_settings_file()
    {
        $this->skipOnGitHubActions();;

        $settings = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

        $this->assertEquals(12345, $settings->port);
        $this->assertEquals('http://otherhost', $settings->host);
    }

    protected function skipOnGitHubActions(): void
    {
        if ($_ENV('CI')) {
            $this->markTestSkipped('Test does not run on GitHub actions');
        }
    }
}
