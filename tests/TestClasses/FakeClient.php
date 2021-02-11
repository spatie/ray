<?php

namespace Spatie\Ray\Tests\TestClasses;

use Spatie\Ray\Client;
use Spatie\Ray\Request;

class FakeClient extends Client
{
    /** @var array */
    protected $sentRequests = [];

    public function serverIsAvailable(): bool
    {
        return true;
    }

    public function changePortAndReturnOriginal(int $newPortNumber): int
    {
        $result = $this->portNumber;

        $this->portNumber = $newPortNumber;

        return $result;
    }

    public function send(Request $request): void
    {
        $requestProperties = $request->toArray();

        foreach ($requestProperties['payloads'] as &$payload) {
            $payload['origin']['file'] = $payload['origin']['file'] = str_replace($this->baseDirectory(), '', $payload['origin']['file']);

            if (isset($payload['content']['values']) && isset($payload['content']['values'][0])) {
                if (! is_bool($payload['content']['values'][0])) {
                    $payload['content']['values'] = preg_replace('/sf-dump-[0-9]{1,10}/', 'sf-dump-xxxxxxxxxx', $payload['content']['values']);
                }
            }

            $payload['origin']['file'] = $this->convertToUnixPath($payload['origin']['file']);
            $payload['origin']['line_number'] = 'xxx';
        }

        $requestProperties['meta'] = [];

        $this->sentRequests[] = $requestProperties;
    }

    public function sentPayloads(): array
    {
        return $this->sentRequests;
    }

    public function reset(): self
    {
        $this->sentRequests = [];

        return $this;
    }

    protected function baseDirectory(): string
    {
        return str_replace("/tests/TestClasses", '', __DIR__);
    }

    protected function convertToUnixPath(string $path): string
    {
        $path = str_replace('D:\a\ray\ray', '', $path);

        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}
