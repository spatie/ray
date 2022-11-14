<?php

use Spatie\Ray\Tests\TestClasses\FakeRay;
use function PHPUnit\Framework\assertEquals;

it('sets small and large payload sizes', function () {
    $ray = new FakeRay();

    $ray->small();
    assertEquals('sm', $ray->getLastSize());

    $ray->large();
    assertEquals('lg', $ray->getLastSize());
    assertEquals(['sm', 'lg'], $ray->getSizeHistory());
});

it('sets the same color payload as the method name', function () {
    $colors = [
        'blue', 'gray', 'green', 'orange', 'purple', 'red',
    ];

    $ray = new FakeRay();

    foreach ($colors as $colorName) {
        $ray->{$colorName}();
        assertEquals($colorName, $ray->getLastColor());
    }

    assertEquals($colors, $ray->getColorHistory());
});

it('sets the same screen color payload as the method name', function () {
    $colors = [
        'blue', 'gray', 'green', 'orange', 'purple', 'red',
    ];

    $ray = new FakeRay();

    foreach ($colors as $colorName) {
        $methodName = 'screen' . ucfirst($colorName);

        $ray->{$methodName}();
        assertEquals($colorName, $ray->getLastScreenColor());
    }

    assertEquals($colors, $ray->getScreenColorHistory());
});
