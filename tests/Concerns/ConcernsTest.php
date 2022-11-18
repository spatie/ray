<?php

use function PHPUnit\Framework\assertEquals;

use Spatie\Ray\Tests\TestClasses\FakeRay;

it('sets small and large payload sizes', function () {
    $ray = new FakeRay();

    $ray->small();
    expect($ray->getLastSize())->toEqual('sm');

    $ray->large();
    expect($ray->getLastSize())->toEqual('lg');
    expect($ray->getSizeHistory())->toEqual(['sm', 'lg']);
});

it('sets the same color payload as the method name', function () {
    $colors = [
        'blue', 'gray', 'green', 'orange', 'purple', 'red',
    ];

    $ray = new FakeRay();

    foreach ($colors as $colorName) {
        $ray->{$colorName}();
        expect($ray->getLastColor())->toEqual($colorName);
    }

    expect($ray->getColorHistory())->toEqual($colors);
});

it('sets the same screen color payload as the method name', function () {
    $colors = [
        'blue', 'gray', 'green', 'orange', 'purple', 'red',
    ];

    $ray = new FakeRay();

    foreach ($colors as $colorName) {
        $methodName = 'screen' . ucfirst($colorName);

        $ray->{$methodName}();
        expect($ray->getLastScreenColor())->toEqual($colorName);
    }

    expect($ray->getScreenColorHistory())->toEqual($colors);
});
