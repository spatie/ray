<?php

namespace Spatie\Ray\Tests;

use Spatie\Ray\Client;
use PHPUnit\Framework\TestCase;
use Spatie\Ray\Request;

class ClientTest extends TestCase
{

    public function it_checks_for_existing_locks()
    {

    }

    /** @test */
    public function it_can_send_data()
    {
        $client = new Client(23517, 'localhost');

//        $client->performAvailabilityCheck();

        print_r(['serverIsAvailable', $client->serverIsAvailable()]);

        $client->send(new Request('aaaa-bbbb', [], []));
        $client->send(new Request('aaaa-bbbb', [], []));
        //$a = microtime(true);
        sleep(12);
        //print_r(['4 sec = ', microtime(true) - $a]);
        $client->send(new Request('aaaa-bbbb', [], []));

        $this->markTestIncomplete();
    }
}
