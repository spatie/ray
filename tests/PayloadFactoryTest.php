<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\Ray\PayloadFactory;
use Spatie\Ray\Payloads\CarbonPayload;
use function PHPUnit\Framework\assertContainsOnlyInstancesOf;

it('accepts carboninterface instances as carbonpayload argument', function () {
    /** @var CarbonPayload[] $payloads */
    $payloads = (new PayloadFactory([
        Carbon::now(),
        CarbonImmutable::now(),
    ]))->getPayloads();

    assertContainsOnlyInstancesOf(CarbonPayload::class, $payloads);
});
