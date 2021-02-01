<?php

namespace Spatie\Ray\Tests;

use Spatie\Ray\ArgumentConverter;
use PHPUnit\Framework\TestCase;

class ArgumentConverterTest extends TestCase
{

    /** @test */
    public function it_returns_null_for_null_values()
    {
        $this->assertNull(ArgumentConverter::convertToPrimitive(null));
    }

    /** @test */
    public function it_returns_int_values_unchanged()
    {
        $this->assertEquals(1, ArgumentConverter::convertToPrimitive(1));
        $this->assertEquals(PHP_INT_MAX, ArgumentConverter::convertToPrimitive(PHP_INT_MAX));
    }

    /** @test */
    public function it_returns_string_values_unchanged()
    {
        $this->assertEquals('test string', ArgumentConverter::convertToPrimitive('test string'));
        $this->assertEquals('', ArgumentConverter::convertToPrimitive(''));
    }

    /** @test */
    public function it_returns_bool_values_unchanged()
    {
        $this->assertTrue(ArgumentConverter::convertToPrimitive(true));
        $this->assertFalse(ArgumentConverter::convertToPrimitive(false));
    }
}
