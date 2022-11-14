<?php

use Spatie\Ray\Exceptions\CouldNotConnectToRay;
use function PHPUnit\Framework\assertStringContainsString;

it('displays the hostname and port it failed to connect to', function () {
    $exception = CouldNotConnectToRay::make('myhost', 12345);

    assertStringContainsString('myhost:12345', $exception->getMessage());
});
