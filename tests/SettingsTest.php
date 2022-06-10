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
        $this->skipOnGitHubActions();

        $settings = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

        $this->assertEquals(12345, $settings->port);
        $this->assertEquals('http://otherhost', $settings->host);
    }

    /** @test */
    public function it_can_find_the_settings_file_more_than_once()
    {
        $this->skipOnGitHubActions();

        $settings1 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

        $this->assertEquals(12345, $settings1->port);
        $this->assertEquals('http://otherhost', $settings1->host);

        $settings2 = SettingsFactory::createFromConfigFile(__DIR__ . Ray::makePathOsSafe('/testSettings/subDirectory/subSubDirectory'));

        $this->assertEquals(12345, $settings2->port);
        $this->assertEquals('http://otherhost', $settings2->host);
    }

    /** @test */
    public function it_can_create_settings_from_an_Array()
    {
        $settings = SettingsFactory::createFromArray(['enabled' => false, 'port' => 1234]);

        self::assertFalse($settings->enabled);
        self::assertSame(1234, $settings->port);
    }

    protected function skipOnGitHubActions(): void
    {
        if (getenv('CI')) {
            $this->markTestSkipped('Test does not run on GitHub actions');
        }
    }
}
