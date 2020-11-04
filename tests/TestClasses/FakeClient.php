<?php

namespace Spatie\Timber\Tests\TestClasses;

use Spatie\Timber\Client;
use Spatie\Timber\Request;

class FakeClient extends Client
{
    protected array $sentPayloads = [];

    public function send(Request $request)
    {
        $json = $request->toJson();

        $canonicalizedJson = preg_replace('/sf-dump-[0-9]{1,10}/', 'sf-dump-xxxxxxxxxx', $json);

        $this->sentPayloads[] = $canonicalizedJson;
    }

    public function sentPayloads(): array
    {
        return $this->sentPayloads;
    }
}
