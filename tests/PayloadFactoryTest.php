<?php

namespace Spatie\Ray\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use Spatie\Ray\PayloadFactory;
use Spatie\Ray\Payloads\CarbonPayload;

class PayloadFactoryTest extends TestCase
{

    /** @test */
    public function it_accepts_carboninterface_instances_as_carbonpayload_argument(): void
    {
        /** @var CarbonPayload[] $payloads */
        $payloads = (new PayloadFactory([
            Carbon::now(),
            CarbonImmutable::now(),
        ]))->getPayloads();

        self::assertContainsOnlyInstancesOf(CarbonPayload::class, $payloads);
    }
}
