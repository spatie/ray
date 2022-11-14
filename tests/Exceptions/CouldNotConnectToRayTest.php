<?php

use function PHPUnit\Framework\assertStringContainsString;

use Spatie\Ray\Exceptions\CouldNotConnectToRay;

it('displays the hostname and port it failed to connect to', function () {
    $exception = CouldNotConnectToRay::make('myhost', 12345);

    assertStringContainsString('myhost:12345', $exception->getMessage());
});
