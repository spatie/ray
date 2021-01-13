<?php


namespace Spatie\Ray\Tests\Concerns;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Tests\TestClasses\FakeRay;

class ConcernsTest extends TestCase
{
    /** @test */
    public function it_sets_small_and_large_payload_sizes()
    {
        $ray = new FakeRay();

        $ray->small();
        $this->assertEquals('sm', $ray->getLastSize());

        $ray->large();
        $this->assertEquals('lg', $ray->getLastSize());
        $this->assertEquals(['sm', 'lg'], $ray->getSizeHistory());
    }

    /** @test */
    public function it_sets_the_same_color_payload_as_the_method_name()
    {
        $colors = [
            'blue', 'gray', 'green', 'orange', 'purple', 'red',
        ];

        $ray = new FakeRay();

        foreach ($colors as $colorName) {
            $ray->{$colorName}();
            $this->assertEquals($colorName, $ray->getLastColor());
        }

        $this->assertEquals($colors, $ray->getColorHistory());
    }
}
