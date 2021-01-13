<?php

namespace Spatie\Ray\Tests\Exceptions;

use Spatie\Ray\Exceptions\CouldNotConnectToRay;
use PHPUnit\Framework\TestCase;

class CouldNotConnectToRayTest extends TestCase
{
    /** @test */
    public function it_displays_the_hostname_and_port_it_failed_to_connect_to()
    {
        $exception = CouldNotConnectToRay::make('myhost', 12345);

        $this->assertStringContainsString('myhost:12345', $exception->getMessage());
    }
}
