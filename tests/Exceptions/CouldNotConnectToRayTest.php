<?php


use Spatie\Ray\Exceptions\CouldNotConnectToRay;

it('displays the hostname and port it failed to connect to', function () {
    $exception = CouldNotConnectToRay::make('myhost', 12345);

    expect($exception->getMessage())->toContain('myhost:12345');
});
